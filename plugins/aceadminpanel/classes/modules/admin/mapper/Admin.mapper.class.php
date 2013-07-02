<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.382
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleAdmin_MapperAdmin extends Mapper
{

    public function GetAdminValue($xKey, $xDefault = null)
    {
        $sValue = null;
        if (is_array($xKey)) {
            $sql = "SELECT adminset_val FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key IN (?a) ";
            $aRows = @$this->oDb->select($sql, $xKey);
            if ($aRows AND is_array($aRows)) $sValue = $aRows[0]['adminset_val'];
        } elseif (strpos($xKey, 'prefix:') === 0) {
            $sql = "SELECT adminset_key AS `key`, adminset_val AS `val` FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key LIKE ? ";
            $aRows = @$this->oDb->select($sql, substr($xKey, 7) . '%');
            if ($aRows) $sValue = $aRows;
            else $sValue = array();
        } else {
            $sql = "SELECT adminset_val FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key = ? ";
            $sValue = @$this->oDb->selectCell($sql, $xKey);
        }
        if ($sValue === null AND $xDefault !== null) $sValue = $xDefault;
        return $sValue;
    }

    public function SetAdminValue($sKey, $sValue)
    {
        $sql = "SELECT adminset_val FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key =? ";
        $row = $this->oDb->selectRow($sql, $sKey);
        if ($row) {
            $sOldValue = $row['adminset_val'];
            if ($sValue != $sOldValue) {
                $bOk = $this->oDb->query("UPDATE " . Config::Get('db.table.adminset') . " SET adminset_val=? WHERE adminset_key=? ", $sValue, $sKey);
            } else {
                $bOk = true;
            }
        } else {
            $sOldValue = null;
            $bOk = $this->oDb->query("INSERT INTO " . Config::Get('db.table.adminset') . " (adminset_key, adminset_val) VALUES(?, ?)", $sKey, $sValue);
        }
        return Array('result' => $bOk, 'old_value' => $sOldValue);
    }

    public function DelAdminValue($xKey)
    {
        if (is_array($xKey)) {
            $sql = "DELETE FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key IN (?a) ";
            $nResult = @$this->oDb->query($sql, $xKey);
        } elseif (strpos($xKey, 'prefix:') === 0) {
            $sql = "DELETE FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key LIKE ? ";
            $nResult = @$this->oDb->query($sql, substr($xKey, 7) . '%');
        } else {
            $sql = "DELETE FROM " . Config::Get('db.table.adminset') . " WHERE adminset_key=? ";
            $nResult = @$this->oDb->query($sql, $xKey);
        }
        return $nResult;
    }

    public function CheckDbo($aInfo, $sFile)
    {
        if (defined('LOCALHOST') AND LOCALHOST) {
            return 'ok (local)';
        }
        $sFile = 'http://' . $sFile . '/checkupdate/chk.php?q=' . urlencode(serialize($aInfo));
        $sResult = @file_get_contents($sFile);
        return $sResult;
    }

    protected function BuildUserFilter($aFilter)
    {
        $sWhere = '(1=1) ';
        if ($aFilter) {
            if (isset($aFilter['login'])) $sWhere .= "AND (user_login='" . $aFilter['login'] . "') ";
            if (isset($aFilter['like'])) $sWhere .= "AND (user_login LIKE '" . $aFilter['like'] . "%') ";
            if (isset($aFilter['email'])) $sWhere .= "AND (user_mail LIKE '" . $aFilter['email'] . "%') ";
            if (isset($aFilter['admin'])) $sWhere .= "AND (ua.user_id>0) ";
            if (isset($aFilter['ip'])) {
                $ip1 = $ip2 = $aFilter['ip'];
                if (strpos($aFilter['ip'], '*') !== false) {
                    $ip1 = str_replace('*', '0', $ip1);
                    $ip2 = str_replace('*', '255', $ip2);
                }
                /* form 0.3
                $sWhere.="AND (".
                        "(INET_ATON(user_ip_register) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) OR ".
                        "(INET_ATON(user_ip_last) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) ".
                        ")";
                 *
                */
                $sWhere .= "AND (" .
                    "(INET_ATON(user_ip_register) BETWEEN INET_ATON('" . $ip1 . "') AND INET_ATON('" . $ip2 . "')) OR " .
                    "(INET_ATON(session_ip_last) BETWEEN INET_ATON('" . $ip1 . "') AND INET_ATON('" . $ip2 . "')) " .
                    ")";
            }
            if (isset($aFilter['regdate'])) {
                $nY = intVal(substr($aFilter['regdate'], 0, 4));
                if ($nY) $sWhere .= "AND (YEAR(user_date_register)=" . $nY . ") ";
                if (strlen($aFilter['regdate']) > 5) {
                    $nM = intVal(substr($aFilter['regdate'], 5, 2));
                    if ($nM) $sWhere .= "AND (MONTH(user_date_register)=" . $nM . ") ";
                }
                if (strlen($aFilter['regdate']) > 8) {
                    $nD = intVal(substr($aFilter['regdate'], 8, 2));
                    if ($nD) $sWhere .= "AND (DAYOFMONTH(user_date_register)=" . $nD . ") ";
                }
            }
        }
        return $sWhere;
    }

    protected function BuildUserSort($aSort)
    {
        $sSort = '';
        if (isset($aSort['id'])) {
            $sSort = 'user_id ';
            if ($aSort['id'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }
        if (isset($aSort['login'])) {
            $sSort = 'user_login ';
            if ($aSort['login'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }
        if (isset($aSort['regdate'])) {
            $sSort = 'user_date_register ';
            if ($aSort['regdate'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }
        if (isset($aSort['reg_ip'])) {
            $sSort = 'INET_ATON(user_ip_register) ';
            if ($aSort['reg_ip'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }
        if (isset($aSort['activated'])) {
            $sSort = 'user_date_activate ';
            if ($aSort['activated'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }

        if (isset($aSort['last_date'])) {
            $sSort = 'session_date_last ';
            if ($aSort['last_date'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }
        if (isset($aSort['last_ip'])) {
            $sSort = 'INET_ATON(session_ip_last) ';
            if ($aSort['last_ip'] == 1) $sSort .= 'ASC'; else $sSort .= 'DESC';
        }

        if (!$sSort) $sSort = 'user_id ASC';
        return ($sSort);
    }

    public function GetUserList(&$iCount, $iCurrPage, $iPerPage, $aFilter = Array(), $aSort = Array())
    {
        $aReturn = array();

        $sFieldList =
            "u.user_id, user_login, user_date_register, user_skill, user_mail,
            user_rating, user_activate, user_date_activate, user_date_comment_last,
            user_ip_register, user_profile_avatar, 
            IF(ua.user_id IS NULL,0,1) as user_is_administrator,
            ab.banline, ab.banunlim, ab.banactive,
            session_ip_create, session_ip_last, session_date_create, session_date_last 
            ";
        $sWhere = $this->BuildUserFilter($aFilter);
        $sOrder = $this->BuildUserSort($aSort);

        $sql =
            "SELECT " . $sFieldList . "
                FROM
                    " . Config::Get('db.table.user') . " AS u
                LEFT JOIN " . Config::Get('db.table.adminban') . " AS ab ON u.user_id=ab.user_id
                LEFT JOIN " . Config::Get('db.table.user_administrator') . " AS ua ON u.user_id=ua.user_id
                LEFT JOIN " . Config::Get('db.table.session') . " AS us ON u.user_id=us.user_id AND us.session_key=
                    (SELECT session_key FROM " . Config::Get('db.table.session') . " AS us1 WHERE us1.user_id=u.user_id AND us1.session_date_last=(SELECT MAX(session_date_last) FROM " . Config::Get('db.table.session') . " AS us2 WHERE us2.user_id=u.user_id LIMIT 1) LIMIT 1)
                WHERE
                    " . $sWhere . "
                ORDER BY " . $sOrder . "
                LIMIT ?d, ?d
                ";

        $aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage);

        if ($aRows) {
            foreach ($aRows as $aRow) {
                //$aReturn[] = new PluginAceadminpanel_ModuleAdmin_EntityUser($aRow);
                $aReturn[] = Engine::GetEntity('User', $aRow);
            }
        }
        return $aReturn;
    }

    public function GetCountCommentsByUserId($sUserId)
    {
        $sql = "SELECT Count(*) AS cnt FROM " . Config::Get('db.table.comment') . " WHERE target_type='topic' AND user_id=?";
        $n = $this->oDb->selectCell($sql, $sUserId);
        return $this->oDb->selectCell($sql, $sUserId);
    }

    public function GetCountTopicsByUserId($sUserId)
    {
        $sql = "SELECT Count(*) AS cnt FROM " . Config::Get('db.table.topic') . " WHERE user_id=?";
        return $this->oDb->selectCell($sql, $sUserId);
    }

    public function GetUserById($nUserId)
    {
        $sql =
            "SELECT
                u.*,
                IF(ua.user_id IS NULL,0,1) as user_is_administrator,
                ab.banline, ab.banunlim, ab.bancomment, ab.banactive,
                INET_ATON('" . func_getIp() . "') as ipn
            FROM
                " . Config::Get('db.table.user') . " as u
            LEFT JOIN " . Config::Get('db.table.user_administrator') . " AS ua ON u.user_id=ua.user_id
            LEFT JOIN " . Config::Get('db.table.adminban') . " AS ab ON u.user_id=ab.user_id
            WHERE
                u.user_id = ? ";
        if (($aRow = @$this->oDb->selectRow($sql, $nUserId))) {
            $aRow['topics_count'] = $this->GetCountTopicsByUserId($nUserId);
            $aRow['comments_count'] = $this->GetCountCommentsByUserId($nUserId);
            $sql = "
                SELECT id, banunlim, banline
                FROM " . Config::Get('db.table.adminips') . "
                WHERE (? BETWEEN ip1 AND ip2) AND (banactive=1) AND (banunlim>0 OR Now()<banline) ";
            if (($aIpRow = $this->oDb->selectRow($sql, $aRow['ipn']))) {
                $aRow['ban_ip'] = $aIpRow['id'];
                $aRow['banunlim'] = $aIpRow['banunlim'];
                $aRow['banline'] = $aIpRow['banline'];
            } else {
                $aRow['ban_ip'] = 0;
            }
            return Engine::GetEntity('User', $aRow); //new PluginAceadminpanel_ModuleAdmin_EntityUser($aRow);
        }
        return null;
    }


    public function GetUserId($sUserLogin)
    {
        $sql = "SELECT user_id FROM " . Config::Get('db.table.user') . " WHERE Lower(user_login)=? LIMIT 1";
        return $this->oDb->selectCell($sql, mb_strtolower($sUserLogin, 'UTF-8'));
    }

    public function CheckUserAdminById($nUserId)
    {
        $sql = "SELECT user_id FROM " . Config::Get('db.table.user_administrator') . " WHERE user_id=?d LIMIT 1";
        return $this->oDb->selectCell($sql, $nUserId);
    }

    public function SetUserBan($nUserId, $dDate, $nUnlim, $sComment = null)
    {

        $sql = "SELECT user_id FROM " . Config::Get('db.table.adminban') . " WHERE user_id=?";
        if ($this->oDb->selectCell($sql, $nUserId)) {
            $sql = "UPDATE " . Config::Get('db.table.adminban') . " SET bandate=?, banline=?, banunlim=?, bancomment=? WHERE user_id=?";
            $result = $this->oDb->query($sql, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment, $nUserId);
        } else {
            $sql = "INSERT INTO " . Config::Get('db.table.adminban') . "(user_id, bandate, banline, banunlim, bancomment) VALUES(?, ?, ?, ?, ?)";
            $result = $this->oDb->query($sql, $nUserId, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment);
        }
        return $result;
    }

    public function SetBanUser($nUserId, $dDate, $nUnlim, $sComment = null)
    {
        $sql = "SELECT user_id FROM " . Config::Get('db.table.adminban') . " WHERE user_id=?";
        if ($this->oDb->selectCell($sql, $nUserId)) {
            $sql = "UPDATE " . Config::Get('db.table.adminban') . " SET bandate=?, banline=?, banunlim=?, bancomment=?, banactive=1 WHERE user_id=?";
            $result = $this->oDb->query($sql, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment, $nUserId);
        } else {
            $sql = "INSERT INTO " . Config::Get('db.table.adminban') . "(user_id, bandate, banline, banunlim, bancomment, banactive) VALUES(?, ?, ?, ?, ?, 1)";
            $result = $this->oDb->query($sql, $nUserId, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment);
        }
        return $result;
    }

    public function DelBanUser($nUserId)
    {
        $sql = "UPDATE " . Config::Get('db.table.adminban') . " SET banactive=0, banunlim=0 WHERE user_id=?";
        $result = $this->oDb->query($sql, $nUserId);
        return $result;
    }

    public function GetBanList(&$iCount, $iCurrPage, $iPerPage, $aFilter = Array(), $aSort = Array())
    {
        $aReturn = array();
        $sFieldList =
            "u.user_id, user_login, user_date_register, user_skill,
            user_rating, user_activate, user_date_activate, user_date_comment_last,
            user_ip_register, user_profile_avatar, 
            IF(ua.user_id IS NULL,0,1) as user_is_administrator,
            ab.banline, ab.banunlim, ab.bancomment, ab.banactive
            ";

        $sWhere = $this->BuildUserFilter($aFilter);
        $sOrder = $this->BuildUserSort($aSort);

        $sql =
            "SELECT u.user_id AS ARRAY_KEY, " . $sFieldList . "
            FROM
                " . Config::Get('db.table.user') . " AS u
            LEFT JOIN " . Config::Get('db.table.adminban') . " AS ab ON u.user_id=ab.user_id
            LEFT JOIN " . Config::Get('db.table.user_administrator') . " AS ua ON u.user_id=ua.user_id
            LEFT JOIN " . Config::Get('db.table.session') . " AS s ON u.user_id=s.user_id
            WHERE (ab.user_id>0) AND (ab.banunlim>0 OR (Now()<ab.banline AND ab.banactive=1)) " .
                " AND " . $sWhere . "
            ORDER BY " . $sOrder . "
            LIMIT ?d, ?d
            ";
        $aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage);
        return $aRows;
        /*
        if ($aRows) {
            foreach ($aRows as $aRow) {
                $aReturn[$aRow['user_id']] = Engine::GetEntity('User', $aRow);
            }
        }
        return $aReturn;
        */
    }

    public function GetBanListIp(&$iCount, $iCurrPage, $iPerPage)
    {
        $aReturn = array();

        $sql =
            "SELECT
                ips.id,
                CASE WHEN ips.ip1<>0 THEN INET_NTOA(ips.ip1) ELSE '' END AS `ip1`,
                CASE WHEN ips.ip2<>0 THEN INET_NTOA(ips.ip2) ELSE '' END AS `ip2`,
                ips.bandate, ips.banline, ips.banunlim, ips.bancomment
            FROM
            " . Config::Get('db.table.adminips') . " AS ips
            WHERE banactive=1
            ORDER BY ips.id
            LIMIT ?d, ?d
        ";
        $aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage);

        if ($aRows) {
            $aReturn = $aRows;
        }
        return $aReturn;
    }

    public function SetBanIp($sIp1, $sIp2, $dDate, $nUnlim, $sComment)
    {
        $sql =
            "INSERT INTO " . Config::Get('db.table.adminips') . "(ip1, ip2, bandate, banline, banunlim, bancomment, banactive)
            VALUES(INET_ATON(?), INET_ATON(?), ?, ?, ?, ?, 1)";
        $result = $this->oDb->query($sql, $sIp1, $sIp2, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment);

        return $result;
    }

    public function DelBanIp($nId)
    {
        $sql = "UPDATE " . Config::Get('db.table.adminips') . " SET banactive=0, banunlim=0 WHERE id=?d";
        $result = $this->oDb->query($sql, $nId);

        return $result;
    }

    public function IsBanIp($sIp)
    {
        $sql =
            "SELECT Count(*)
            FROM " . Config::Get('db.table.adminips') . "
            WHERE (INET_ATON(?) BETWEEN ip1 AND ip2) AND (banactive=1)";
        $result = $this->oDb->selectCell($sql, $sIp);
        return $result;
    }

    public function GetUserVoteStat0($sUserId)
    {
        $aResult = Array(
            'cnt_topics_m' => 0, 'cnt_topics_p' => 0, 'sum_topics_m' => 0.0, 'sum_topics_p' => 0.0,
            'cnt_users_m' => 0, 'cnt_users_p' => 0, 'sum_users_m' => 0.0, 'sum_users_p' => 0.0,
            'cnt_comments_m' => 0, 'cnt_comments_p' => 0, 'sum_comments_m' => 0.0, 'sum_comments_p' => 0.0,
        );

        $sql =
            "SELECT
                Sum(CASE WHEN vote_delta<0 THEN 1 ELSE 0 END) AS cnt_topics_m,
                Sum(CASE WHEN vote_delta>0 THEN 1 ELSE 0 END) AS cnt_topics_p,
                Sum(CASE WHEN vote_delta<0 THEN vote_delta ELSE 0 END) AS sum_topics_m,
                Sum(CASE WHEN vote_delta>0 THEN vote_delta ELSE 0 END) AS sum_topics_p
            FROM " . Config::Get('db.table.topic_vote') . "
            WHERE user_voter_id=?";
        $aRow = $this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_topics_m'] = intVal($aRow['cnt_topics_m']);
            $aResult['cnt_topics_p'] = intVal($aRow['cnt_topics_p']);
            $aResult['sum_topics_m'] = sprintf('%0.4f', $aRow['sum_topics_m']);
            $aResult['sum_topics_p'] = sprintf('%0.4f', $aRow['sum_topics_p']);
        }

        $sql =
            "SELECT
                Sum(CASE WHEN vote_delta<0 THEN 1 ELSE 0 END) AS cnt_users_m,
                Sum(CASE WHEN vote_delta>0 THEN 1 ELSE 0 END) AS cnt_users_p,
                Sum(CASE WHEN vote_delta<0 THEN vote_delta ELSE 0 END) AS sum_users_m,
                Sum(CASE WHEN vote_delta>0 THEN vote_delta ELSE 0 END) AS sum_users_p
            FROM " . Config::Get('db.table.user_vote') . "
            WHERE user_voter_id=?";
        $aRow = $this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_users_m'] = intVal($aRow['cnt_users_m']);
            $aResult['cnt_users_p'] = intVal($aRow['cnt_users_p']);
            $aResult['sum_users_m'] = sprintf('%0.4f', $aRow['sum_users_m'], 4);
            $aResult['sum_users_p'] = sprintf('%0.4f', $aRow['sum_users_p']);
        }

        $sql =
            "SELECT
                Sum(CASE WHEN vote_delta<0 THEN 1 ELSE 0 END) AS cnt_comments_m,
                Sum(CASE WHEN vote_delta>0 THEN 1 ELSE 0 END) AS cnt_comments_p,
                Sum(CASE WHEN vote_delta<0 THEN vote_delta ELSE 0 END) AS sum_comments_m,
                Sum(CASE WHEN vote_delta>0 THEN vote_delta ELSE 0 END) AS sum_comments_p
            FROM " . Config::Get('db.table.topic_comment_vote') . "
            WHERE user_voter_id=?";
        $aRow = $this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_comments_m'] = intVal($aRow['cnt_comments_m']);
            $aResult['cnt_comments_p'] = intVal($aRow['cnt_comments_p']);
            $aResult['sum_comments_m'] = sprintf('%0.4f', $aRow['sum_comments_m']);
            $aResult['sum_comments_p'] = sprintf('%0.4f', $aRow['sum_comments_p']);
        }

        return $aResult;
    }

    public function GetUserVoteStat($sUserId)
    {
        $aResult = Array(
            'cnt_topics_m' => 0, 'cnt_topics_p' => 0, 'sum_topics_m' => 0.0, 'sum_topics_p' => 0.0,
            'cnt_users_m' => 0, 'cnt_users_p' => 0, 'sum_users_m' => 0.0, 'sum_users_p' => 0.0,
            'cnt_comments_m' => 0, 'cnt_comments_p' => 0, 'sum_comments_m' => 0.0, 'sum_comments_p' => 0.0,
        );

        $sql =
            "SELECT
                Sum(CASE WHEN vote_value<0 AND target_type='topic' THEN 1 ELSE 0 END) AS cnt_topics_m,
                Sum(CASE WHEN vote_value>0 AND target_type='topic'  THEN 1 ELSE 0 END) AS cnt_topics_p,
                Sum(CASE WHEN vote_value<0 AND target_type='topic'  THEN vote_value ELSE 0 END) AS sum_topics_m,
                Sum(CASE WHEN vote_value>0 AND target_type='topic'  THEN vote_value ELSE 0 END) AS sum_topics_p,
                Sum(CASE WHEN vote_value<0 AND target_type='user' THEN 1 ELSE 0 END) AS cnt_users_m,
                Sum(CASE WHEN vote_value>0 AND target_type='user' THEN 1 ELSE 0 END) AS cnt_users_p,
                Sum(CASE WHEN vote_value<0 AND target_type='user' THEN vote_value ELSE 0 END) AS sum_users_m,
                Sum(CASE WHEN vote_value>0 AND target_type='user' THEN vote_value ELSE 0 END) AS sum_users_p,
                Sum(CASE WHEN vote_value<0 AND target_type='comment' THEN 1 ELSE 0 END) AS cnt_comments_m,
                Sum(CASE WHEN vote_value>0 AND target_type='comment' THEN 1 ELSE 0 END) AS cnt_comments_p,
                Sum(CASE WHEN vote_value<0 AND target_type='comment' THEN vote_value ELSE 0 END) AS sum_comments_m,
                Sum(CASE WHEN vote_value>0 AND target_type='comment' THEN vote_value ELSE 0 END) AS sum_comments_p
            FROM " . Config::Get('db.table.vote') . "
            WHERE user_voter_id=?";
        $aRow = $this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_topics_m'] = intVal($aRow['cnt_topics_m']);
            $aResult['cnt_topics_p'] = intVal($aRow['cnt_topics_p']);
            $aResult['sum_topics_m'] = sprintf('%0.3f', $aRow['sum_topics_m']);
            $aResult['sum_topics_p'] = sprintf('%0.3f', $aRow['sum_topics_p']);
            $aResult['cnt_users_m'] = intVal($aRow['cnt_users_m']);
            $aResult['cnt_users_p'] = intVal($aRow['cnt_users_p']);
            $aResult['sum_users_m'] = sprintf('%0.3f', $aRow['sum_users_m'], 4);
            $aResult['sum_users_p'] = sprintf('%0.3f', $aRow['sum_users_p']);
            $aResult['cnt_comments_m'] = intVal($aRow['cnt_comments_m']);
            $aResult['cnt_comments_p'] = intVal($aRow['cnt_comments_p']);
            $aResult['sum_comments_m'] = sprintf('%0.3f', $aRow['sum_comments_m']);
            $aResult['sum_comments_p'] = sprintf('%0.3f', $aRow['sum_comments_p']);
        }

        return $aResult;
    }

    public function GetVotedByUserId($sUserId, $iPerPage)
    {
        $sql = "SELECT target_id, target_type, vote_value, topic_title AS title, user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.topic') . " AS t ON t.topic_id=v.target_id
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=t.user_id
		WHERE target_type='topic' AND user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['topics'] = $this->oDb->select($sql, $sUserId, $iPerPage);

        $sql = "SELECT vote_value, blog_title AS title, user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.blog') . " AS b ON b.blog_id=v.target_id
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=b.user_owner_id
		WHERE target_type='blog' AND user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['blogs'] = $this->oDb->select($sql, $sUserId, $iPerPage);

        $sql = "SELECT vote_value, IF(Length(comment_text)>200,Concat(Left(comment_text, 197), '...'), comment_text) AS title,
                user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.comment') . " AS c ON c.comment_id=v.target_id AND c.target_type='topic'
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=c.user_id
		WHERE v.target_type='comment' AND  user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['comments'] = $this->oDb->select($sql, $sUserId, $iPerPage);

        $sql = "SELECT vote_value, user_profile_name AS title, user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=v.target_id
		WHERE target_type='user' AND  user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['users'] = $this->oDb->select($sql, $sUserId, $iPerPage);
        return $aResult;
    }

    public function GetVotesForUserId($nUserId, $iPerPage)
    {
        $sql = "SELECT target_id, target_type, vote_value, topic_title AS title, u.user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.topic') . " AS t ON t.topic_id=v.target_id
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=v.user_voter_id
		WHERE v.target_type='topic' AND t.user_id=?d ORDER BY vote_date DESC LIMIT ?d";
        $aResult['topics'] = $this->oDb->select($sql, $nUserId, $iPerPage);

        $sql = "SELECT vote_value, blog_title AS title, u.user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.blog') . " AS b ON b.blog_id=v.target_id
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=v.user_voter_id
		WHERE target_type='blog' AND b.user_owner_id=?d ORDER BY vote_date DESC LIMIT ?d";
        $aResult['blogs'] = $this->oDb->select($sql, $nUserId, $iPerPage);

        $sql = "SELECT vote_value, IF(Length(comment_text)>200,Concat(Left(comment_text, 197), '...'), comment_text) AS title,
                u.user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.comment') . " AS c ON c.comment_id=v.target_id AND c.target_type='topic'
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=user_voter_id
		WHERE v.target_type='comment' AND c.user_id=?d ORDER BY vote_date DESC LIMIT ?d";
        $aResult['comments'] = $this->oDb->select($sql, $nUserId, $iPerPage);

        $sql = "SELECT vote_value, user_profile_name AS title, u.user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=user_voter_id
		WHERE target_type='user' AND v.target_id=?d ORDER BY vote_date DESC LIMIT ?d";
        $aResult['users'] = $this->oDb->select($sql, $nUserId, $iPerPage);
        return $aResult;
    }

    public function GetUserIps($nUserId, $nPerPage = null)
    {
        $sql = "SELECT target_id, target_type, vote_value, topic_title AS title, u.user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.topic') . " AS t ON t.topic_id=v.target_id
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=v.user_voter_id
		WHERE v.target_type='topic' AND t.user_id=?d ORDER BY vote_date DESC LIMIT ?d";
        $aResult['topics'] = $this->oDb->select($sql, $nUserId, $nPerPage);

        $sql = "SELECT vote_value, blog_title AS title, u.user_login, vote_date
		FROM " . Config::Get('db.table.vote') . " AS v
			LEFT JOIN " . Config::Get('db.table.blog') . " AS b ON b.blog_id=v.target_id
			LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=v.user_voter_id
		WHERE target_type='blog' AND b.user_owner_id=?d ORDER BY vote_date DESC LIMIT ?d";
        $aResult['blogs'] = $this->oDb->select($sql, $nUserId, $nPerPage);

        $sql =
            "SELECT
                'comment' AS action_type, comment_id AS action_id,
                target_type, target_id,
                comment_date AS action_date, comment_user_ip AS action_ip
            FROM " . Config::Get('db.table.comment') . " AS t
                WHERE t.user_id=?d ORDER BY comment_date DESC {LIMIT ?d}";
        $aResult['comments'] = $this->oDb->select($sql, $nUserId, ($nPerPage ? $nPerPage : DBSIMPLE_SKIP));

        $sql =
            "SELECT
                'talk' AS action_type, talk_id AS action_id,
                '' AS target_type, '' AS target_id,
                talk_date AS action_date, talk_user_ip AS action_ip
            FROM " . Config::Get('db.table.talk') . " AS t
                WHERE t.user_id=?d ORDER BY talk_date DESC {LIMIT ?d}";
        $aResult['talk'] = $this->oDb->select($sql, $nUserId, ($nPerPage ? $nPerPage : DBSIMPLE_SKIP));

        $sql =
            "SELECT
                'topic' AS action_type, topic_id AS action_id,
                '' AS target_type, '' AS target_id,
                topic_date_add AS action_date, topic_user_ip AS action_ip
            FROM " . Config::Get('db.table.topic') . " AS t
                WHERE t.user_id=?d ORDER BY topic_date_add DESC {LIMIT ?d}";
        $aResult['topic'] = $this->oDb->select($sql, $nUserId, ($nPerPage ? $nPerPage : DBSIMPLE_SKIP));

        $sql =
            "SELECT
                'topic' AS action_type, topic_id AS action_id,
                '' AS target_type, '' AS target_id,
                topic_date_add AS action_date, topic_user_ip AS action_ip
            FROM " . Config::Get('db.table.topic') . " AS t
                WHERE t.user_id=?d ORDER BY topic_date_add DESC {LIMIT ?d}";
        $aResult['topic'] = $this->oDb->select($sql, $nUserId, ($nPerPage ? $nPerPage : DBSIMPLE_SKIP));

        if (Config::Get('db.table.company_feedback'))
            $sql =
                "SELECT
                'company_feedback' AS action_type, feedback_id AS action_id,
                'company' AS target_type, company_id AS target_id,
                feedback_date AS action_date, feedback_user_ip AS action_ip
            FROM " . Config::Get('db.table.company_feedback') . " AS t
                WHERE t.user_id=?d ORDER BY feedback_date DESC {LIMIT ?d}";
        $aResult['company_feedback'] = $this->oDb->select($sql, $nUserId, ($nPerPage ? $nPerPage : DBSIMPLE_SKIP));
        return $aResult;
    }

    public function AddAdministrator($nUserId)
    {
        $sql = "SELECT user_id FROM " . Config::Get('db.table.user_administrator') . " WHERE user_id=?";
        if (!$this->oDb->selectCell($sql, $nUserId)) {
            $nCnt = $this->oDb->selectCell("SELECT Count(*) FROM " . Config::Get('db.table.user_administrator'));
            $this->oDb->query("INSERT INTO " . Config::Get('db.table.user_administrator') . " (user_id) VALUES(?)", $nUserId);
            $bOk = ($nCnt != $this->oDb->selectCell("SELECT Count(*) FROM " . Config::Get('db.table.user_administrator')));
        }
        return $bOk;
    }

    public function DelAdministrator($nUserId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.user_administrator') . " WHERE user_id=?";
        $bOk = $this->oDb->query($sql, $nUserId);
        return $bOk;
    }

    public function DelUser($nUserId)
    {
        $bOk = true;
        // Удаление комментов

        // находим комменты удаляемого юзера и для каждого:
        // нижележащее дерево комментов подтягиваем к родителю удаляемого
        $sql =
            "SELECT comment_id AS ARRAY_KEY, comment_pid, target_type, target_id
                FROM " . Config::Get('db.table.comment') . "
                WHERE user_id=?d";

        $aTargets = array();
        while ($aComments = $this->oDb->select($sql, $nUserId)) {
            if (is_array($aComments) AND sizeof($aComments)) {
                foreach ($aComments AS $sId => $aCommentData) {
                    $this->oDb->transaction();
                    $sql = "UPDATE " . Config::Get('db.table.comment') . " SET comment_pid=?d WHERE comment_pid=?d";
                    @$this->oDb->query($sql, $aCommentData['comment_pid'], $sId);
                    $sql = "DELETE FROM " . Config::Get('db.table.comment') . " WHERE comment_id=?d";
                    @$this->oDb->query($sql, $sId);
                    if (!isset($aTargets[$aCommentData['target_type'] . '_' . $aCommentData['target_id']]))
                        $aTargets[$aCommentData['target_type'] . '_' . $aCommentData['target_id']] = array(
                            'target_type' => $aCommentData['target_type'],
                            'target_id' => $aCommentData['target_id'],
                        );
                    $this->oDb->commit();
                }
            } else {
                break;
            }
        }
        // Обновление числа комментариев
        $this->updateTopicCountComments($aTargets);

        // удаление остального "хозяйства"
        $this->oDb->transaction();
        $sql = "DELETE FROM " . Config::Get('db.table.topic') . " WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.blog') . " WHERE user_owner_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.vote') . " WHERE user_voter_id=?d";
        @$this->oDb->query($sql, $nUserId);

        //$sql="DELETE FROM ".Config::Get('db.table.topic_comment_vote')." WHERE user_voter_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        //$sql="DELETE FROM ".Config::Get('db.table.user_vote')." WHERE user_voter_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        //$sql="DELETE FROM ".Config::Get('db.table.blog_vote')." WHERE user_voter_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.blog_user') . " WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        //$sql = "DELETE FROM " . Config::Get('db.table.city_user') . " WHERE user_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        //$sql = "DELETE FROM " . Config::Get('db.table.country_user') . " WHERE user_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.adminban') . " WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.talk_user') . " WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.user') . " WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $this->oDb->commit();

        $bOk = $this->oDb->selectCell("SELECT user_id FROM " . Config::Get('db.table.user') . " WHERE user_id=?d", $nUserId);
        return !$bOk;
    }

    /**
     * Чистка ленты по ID пользователя
     *
     * @param $nUserId
     * @return void
     */
    public function ClearStreamByUser($nUserId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.stream_event') . " WHERE user_id=?d";
        //$this->oDb->query($sql, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.stream_event') . " WHERE user_id=?d OR target_user_id=?d";
        //$this->oDb->query($sql, $nUserId, $nUserId);

        $sql = "DELETE FROM " . Config::Get('db.table.stream_event') . " WHERE user_id=?d";
        //$this->oDb->query($sql, $nUserId);

        return true;
    }

    public function ClearStreamByBlog($nBlogId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.stream_event') . " WHERE event_type LIKE '%_blog' AND target_id=?d";
        $this->oDb->query($sql, $nBlogId);
        return true;
    }

    /**
     * Получить типы блогов
     *
     * @return  array
     */
    public function GetBlogTypes()
    {
        $sql = "
            SELECT DISTINCT Count( blog_id ) AS blog_cnt , blog_type
            FROM " . Config::Get('db.table.blog') . " AS b
            GROUP BY blog_type
            ORDER BY blog_type";
        $aRows = $this->oDb->select($sql);
        return $aRows;
    }

    /**
     * Получить все блоги всех типов
     *
     * @param   int     $iCount
     * @param   int     $iCurrPage
     * @param   int     $iPerPage
     * @param   array   $aParams
     * @return  array
     */
    public function GetBlogList(&$iCount, $iCurrPage, $iPerPage, $aParams = array())
    {
        $bBlogExt = Config::Get('plugin.aceblogextender');

        $sWhere = '1=1';
        if (isset($aParams['type']) AND $aParams['type'])
            $sWhere .= " AND (blog_type=" . $this->oDb->escape($aParams['type']) . ")";

        if (isset($aParams['user_id']))
            $sWhere .= " AND (user_owner_id=" . intVal($aParams['user_id']) . ")";

        $sql =
            "SELECT b.blog_id AS ARRAY_KEY, b.blog_id, blog_title, blog_url, blog_rating, blog_count_vote,
                blog_count_user, blog_date_add, blog_date_edit, blog_type,
                user_owner_id, u.user_login
                " . ($bBlogExt ? ",exp.blog_exp_value AS attach_allow, ext.blog_premoderation" : "") . "
            FROM " . Config::Get('db.table.blog') . " AS b
                LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=b.user_owner_id
                " . ($bBlogExt ? "LEFT JOIN " . Config::Get('db.table.mblog_extended') . " AS ext ON (ext.blog_id=b.blog_id)" : "") . "
                " . ($bBlogExt ? "LEFT JOIN " . Config::Get('db.table.mblog_expanded') . " AS exp ON (exp.blog_id=b.blog_id) AND (exp.blog_exp_param='attach_allow')" : "") . "
            WHERE " . $sWhere . "
            ORDER BY blog_id
            LIMIT ?d, ?d";
        if (($aBlogs = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage))) {
            return $aBlogs;
        }
        return array();
    }

    public function GetBlogsByUserId($sUserId)
    {
        $sql =
            "SELECT b.blog_id AS ARRAY_KEY, b.blog_id, blog_title, blog_url, blog_rating, blog_count_vote,
                blog_count_user, blog_date_add, blog_date_edit, blog_type
            FROM " . Config::Get('db.table.blog') . " AS b
            WHERE user_owner_id = ?d";
        if (($aBlogs = $this->oDb->select($sql, $sUserId))) {
            return $aBlogs;
        }
        return array();
    }

    public function AddUserVote($oUserVote)
    {
        $sql =
            "INSERT INTO " . Config::Get('db.table.user_vote') . "
		(user_id, user_voter_id, vote_delta)
            VALUES(?d,  ?d,	?f) ON DUPLICATE KEY UPDATE vote_delta = vote_delta + ?f
            ";
        if ($this->oDb->query($sql, $oUserVote->getUserId(), $oUserVote->getVoterId(), $oUserVote->getDelta(), $oUserVote->getDelta()) === 0) {
            return true;
        }
        return false;
    }

    public function GetInvites(&$iCount, $iCurrPage, $iPerPage, $aParam = array())
    {
        $sSort = 'invite_id';
        $sOrder = 'DESC';
        if (isset($aParam['sort'])) {
            if ($aParam['sort'] == 'code') {
                $sSort = 'i.invite_code';
            } elseif ($aParam['sort'] == 'user_from') {
                $sSort = 'u1.user_login';
            } elseif ($aParam['sort'] == 'date_add') {
                $sSort = 'i.invite_date_add';
            } elseif ($aParam['sort'] == 'user_to') {
                $sSort = 'u2.user_login';
            } elseif ($aParam['sort'] == 'date_used') {
                $sSort = 'i.invite_used';
            } else {
                $sSort = 'invite_id';
            }
        }
        if (isset($aParam['order'])) {
            if ($aParam['order'] == 1) {
                $sOrder = 'DESC';
            } else {
                $sOrder = 'ASC';
            }
        }
        $sql =
            "SELECT invite_id, invite_code, user_from_id, user_to_id,
                invite_date_add, invite_date_used, invite_used,
                u1.user_login AS from_login,
                u2.user_login AS to_login
              FROM " . Config::Get('db.table.invite') . " AS i
                LEFT JOIN " . Config::Get('db.table.user') . " AS u1 ON i.user_from_id=u1.user_id
                LEFT JOIN " . Config::Get('db.table.user') . " AS u2 ON i.user_to_id=u2.user_id
            ORDER BY " . $sSort . " " . $sOrder . "
            LIMIT ?d, ?d";
        if (($aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage))) {
            return $aRows;
        }
        return array();
    }

    public function DelInvites($aIds)
    {
        $sql =
            "DELETE FROM " . Config::Get('db.table.invite') . "
            WHERE invite_id IN (?a) AND invite_used=0 AND invite_date_used IS NULL";
        return $this->oDb->query($sql, $aIds);
    }

    public function GetSiteStat()
    {
        $aResult = array();
        $sql = "SELECT Count(*) FROM " . Config::Get('db.table.user') . " WHERE user_activate>0";
        $aResult['users'] = $this->oDb->selectCell($sql);
        $sql = "SELECT Count(*) FROM " . Config::Get('db.table.blog');
        $aResult['blogs'] = $this->oDb->selectCell($sql);
        $sql = "SELECT Count(*) FROM " . Config::Get('db.table.topic');
        $aResult['topics'] = $this->oDb->selectCell($sql);
        $sql = "SELECT Count(*) FROM " . Config::Get('db.table.comment') . " WHERE target_type='topic'";
        $aResult['comments'] = $this->oDb->selectCell($sql);
        return $aResult;
    }

    public function updateTopicCountComments($aTargets)
    {
        foreach ($aTargets as $aTarget) {
            if ($aTarget['target_type'] == 'topic')
                $sql = "
                    UPDATE " . Config::Get('db.table.topic') . "
                        SET topic_count_comment =
                            (SELECT COUNT(*) AS cnt
                            FROM " . Config::Get('db.table.comment') . "
                                WHERE target_type='topic' AND target_id=?d)
                    WHERE topic_id=?d";
            else
                $sql = "
                    UPDATE " . Config::Get('db.table.talk') . "
                        SET talk_count_comment =
                            (SELECT COUNT(*) AS cnt
                            FROM " . Config::Get('db.table.comment') . "
                                WHERE target_type='talk' AND target_id=?d)
                    WHERE talk_id=?d";
            $this->oDb->query($sql, $aTarget['target_id'], $aTarget['target_id']);
        }
    }

    public function transaction()
    {
        $this->oDb->transaction();
    }

    public function commit()
    {
        $this->oDb->commit();
    }

    public function rollback()
    {
        $this->oDb->rollback();
    }

    protected function _tableName($sTableName)
    {
        return str_replace('prefix_', Config::Get('db.table.prefix'), $sTableName);
    }

    public function isTableExists($sTableName)
    {
        $sTableName = $this->_tableName($sTableName);
        $sql = "
            SHOW TABLES LIKE '" . $sTableName . "'
                ";
        if ($this->oDb->selectCol($sql)) {
            return true;
        }
        return false;
    }

    public function isFieldExists($sTableName, $sFieldName)
    {
        $sTableName = $this->_tableName($sTableName);
        $sql = "
            SHOW COLUMNS FROM " . $sTableName . " WHERE `Field` = '" . $sFieldName . "'
                ";
        if ($this->oDb->selectCol($sql)) {
            return true;
        }
        return false;
    }

    public function isIndexExists($sTableName, $sIndexName)
    {
        $sTableName = $this->_tableName($sTableName);
        $sql = "
            SHOW INDEX FROM " . $sTableName . " WHERE `Key_name` = '" . $sIndexName . "'
                ";
        if ($this->oDb->selectCol($sql)) {
            return true;
        }
        return false;
    }

    public function CreateTableFromXml($xml)
    {
        $sTableName = $this->_tableName($xml['name']);

        $sql = '';
        foreach ($xml->fields->field as $field) {
            if ($sql) $sql .= ",\n";
            $sql .= "  `" . $field['name'] . "` " . $field;
        }
        foreach ($xml->indexes->index as $index) {
            if ($sql) $sql .= ",\n";
            if ($index['name'] == 'PRIMARY') {
                $sql .= "  PRIMARY KEY (" . $index . ")";
            } else {
                $sql .= "  KEY `" . $index['name'] . "` (" . $index . ")";
            }
        }
        $sql = "CREATE TABLE `$sTableName` (\n" . $sql . "\n)";
        if (Config::Get('db.tables.engine'))
            $sql .= ' ENGINE=' . Config::Get('db.tables.engine');
        $sql .= ' DEFAULT CHARSET=utf8;';

        $this->oDb->query($sql);
    }

    public function AddFieldFromXml($sTableName, $field)
    {
        $sTableName = $this->_tableName($sTableName);

        $sql = "ALTER TABLE `$sTableName` ADD `" . $field['name'] . "` " . $field . ";";
        $this->oDb->query($sql);
    }

    public function AddIndexFromXml($sTableName, $index)
    {
        $sTableName = $this->_tableName($sTableName);

        if ($index['name'] == 'PRIMARY') {
            $sql = "ALTER TABLE `$sTableName` ADD PRIMARY KEY (" . $index . ");";
        } elseif ($index['unique']) {
            $sql = "ALTER TABLE `$sTableName` ADD UNIQUE (" . $index . ");";
        } else {
            $sql = "ALTER TABLE `$sTableName` ADD INDEX `" . $index['name'] . "` (" . $index . ");";
        }
        $this->oDb->query($sql);
    }

    public function GetUnlinkedBlogsForUsers()
    {
        $sql = "
            SELECT j.blog_id, u.user_login, j.user_id
            FROM " . Config::Get('db.table.blog_user') . " AS j
                LEFT JOIN " . Config::Get('db.table.blog') . " AS b ON b.blog_id=j.blog_id
                LEFT JOIN " . Config::Get('db.table.user') . " AS u ON u.user_id=j.user_id
            WHERE b.blog_id IS NULL";
        $aRows = $this->oDb->query($sql);
        $aResult = array();
        if ($aRows)
            foreach ($aRows as $aRow) {
                $aResult[$aRow['blog_id']][] = $aRow;
            }
        return $aResult;
    }

    public function DelUnlinkedBlogsForUsers($aBlogIds)
    {
        $sql = "
            DELETE FROM " . Config::Get('db.table.blog_user') . "
            WHERE blog_id IN (?a)
        ";
        $aResult = $this->oDb->query($sql, $aBlogIds);
        return $aResult;
    }

    public function GetUnlinkedBlogsForCommentsOnline()
    {
        $sql = "
            SELECT c.target_parent_id AS blog_id, c.comment_id, c.target_id
            FROM " . Config::Get('db.table.comment_online') . " AS c
                LEFT JOIN " . Config::Get('db.table.topic') . " AS t ON t.topic_id=c.target_id
                LEFT JOIN " . Config::Get('db.table.blog') . " AS b ON b.blog_id=c.target_parent_id
            WHERE c.target_type='topic' AND b.blog_id IS NULL";
        $aRows = $this->oDb->query($sql);
        $aResult = array();
        if ($aRows)
            foreach ($aRows as $aRow) {
                $aResult[$aRow['blog_id']][] = $aRow;
            }
        return $aResult;
    }

    public function DelUnlinkedBlogsForCommentsOnline($aBlogIds)
    {
        $sql = "
            DELETE FROM " . Config::Get('db.table.comment_online') . "
            WHERE target_type='topic' AND target_parent_id IN (?a)
        ";
        $aResult = $this->oDb->query($sql, $aBlogIds);
        return $aResult;
    }


}

// EOF