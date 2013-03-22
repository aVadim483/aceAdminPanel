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


/**
 * Fichier de langue pour le module (langue française)
 */
return array(
    'adm_settings_title' => 'Paramètres',
    'adm_settings_base' => 'Principal',
    'adm_settings_sys' => 'Système',
    'adm_settings_acl' => 'Droits',

    'adm_set_section_site' => 'Site',
    'adm_set_view_skin' => 'Skin (peau) site',
    'adm_set_view_name' => 'Nom du site',
    'adm_set_view_description' => 'La valeur de la méta-tag description',
    'adm_set_view_keywords' => 'La valeur de la méta-tag mots-clés',

    'adm_set_section_general' => 'Général',
    'adm_set_general_close' => 'Utilisez un mode fermé le site',
    'adm_set_general_reg_invite' => 'L\'inscription est disponible sur invitation seulement',
    'adm_set_general_reg_activation' => 'Lorsque vous vous inscrivez pour utiliser l\'activation de l\'utilisateur',

    'adm_set_section_sys_lang' => 'Langues',
    'adm_set_lang_current' => 'La langue courante',
    'adm_set_lang_default' => 'La langue par défaut (s\'il n\'y a pas une phrase dans le langage courant)',

    'adm_set_section_edit' => 'Rédaction',
    'adm_set_view_tinymce' => 'Utilisez l\'éditeur visuel TinyMCE',
    'adm_set_view_noindex' => '"Masquer"liens des moteurs de recherche',
    'adm_set_view_img_resize_width' => 'Jusqu\'à ce que la taille de la largeur (en pixels) des images uzhimat dans le texte',
    'adm_set_view_img_max_width' => 'Largeur maximale de l\'image chargée en pixels',
    'adm_set_view_img_max_height' => 'Hauteur maximale de l\'image chargée en pixels',

    'adm_set_section_sys_cookie'=>'Cookies et sessions',
    'adm_set_sys_cookie_host'=>'Accueil à créer des cookies',
    'adm_set_sys_cookie_path'=>'La façon de créer des cookies',
    'adm_set_sys_session_standart'=>'Utiliser un mécanisme standard pour les sessions',
    'adm_set_sys_session_name'=>'Nom session',
    'adm_set_sys_session_timeout'=>'Timeout de la session en quelques secondes',
    'adm_set_sys_session_host'=>'Les cookies de session d\'accueil',
    'adm_set_sys_session_path'=>'Les cookies de session Path',

    'adm_set_section_sys_mail'=>'Paramètres de courrier électronique',
    'adm_set_sys_mail_type'=>'Quel type utiliser pour l\'envoi',
    'adm_set_sys_mail_from_email'=>'Adresse à laquelle toutes les notifications sont envoyées',
    'adm_set_sys_mail_from_name'=>'Le nom de l\'expéditeur, qui a envoyé tous les avis',
    'adm_set_sys_mail_charset'=>'Qu\'est-ce codage à utiliser dans les lettres',
    'adm_set_sys_mail_smtp_host'=>'Paramètres SMTP - Host',
    'adm_set_sys_mail_smtp_port'=>'Paramètres SMTP - port',
    'adm_set_sys_mail_smtp_user'=>'Paramètres SMTP - User',
    'adm_set_sys_mail_smtp_password'=>'Paramètres SMTP - Mot de passe',
    'adm_set_sys_mail_smtp_auth'=>'Utiliser l\'authentification lors de l\'envoi',
    'adm_set_sys_mail_include_comment'=>'Comprend la notification de commentaire commentaire nouveau texte',
    'adm_set_sys_mail_include_talk'=>'Comprend la notification des nouveaux messages personnels',

    'adm_set_section_sys_logs' => 'Paramètres de l\'exploitation forestière (grumes)',
    'adm_set_sys_logs_sql_query' => 'Mettez de l\'exploitation forestière toutes les requêtes SQL',
    'adm_set_sys_logs_sql_query_file' => 'Le fichier journal SQL-requêtes',
    'adm_set_sys_logs_sql_error' => 'Mettez de l\'exploitation forestière erronée requête SQL',
    'adm_set_sys_logs_sql_error_file' => 'Le fichier journal des erreurs SQL-requêtes',
    'adm_set_sys_logs_profiler' => 'Activer le profilage des processus',
    'adm_set_sys_logs_profiler_file' => 'Le fichier journal profilage processus',
    'adm_set_sys_logs_cron_file' => 'Le fichier journal est géré couronnes processus',

    'adm_set_section_acl' => 'Paramètres de contrôle d\'accès',
    'adm_set_acl_create_blog_rating' => 'Le taux de seuil à partir duquel l\'utilisateur peut créer un blog de l\'équipe',
    'adm_set_acl_create_comment_rating' => 'Le taux de seuil à partir duquel l\'utilisateur peut ajouter des commentaires',
    'adm_set_acl_create_comment_limit_time' => 'Temps (sec) entre les commentaires Commentaires, si 0, alors la restriction ne fonctionne pas',
    'adm_set_acl_create_comment_limit_time_rating' => 'Note ci-dessus, dont le délai expire le poster des commentaires',
    'adm_set_acl_create_topic_limit_time' => 'Temps (sec) entre la création de documents, si 0, alors la restriction ne fonctionne pas',
    'adm_set_acl_create_topic_limit_time_rating' => 'Note ci-dessus, dont le délai expire le la création de documents',
    'adm_set_acl_create_talk_limit_time' => 'Temps (en secondes) entre l\'envoi de courrier interne, si 0, alors la restriction ne fonctionne pas',
    'adm_set_acl_create_talk_limit_time_rating' => 'Note ci-dessus qui cesse d\'exploiter un délai pour l\'envoi de courrier interne',
    'adm_set_acl_create_talk_comment_limit_time' => 'Temps (en secondes) entre l\'envoi de courrier interne',
    'adm_set_acl_create_talk_comment_limit_time_rating' => 'Note ci-dessus qui cesse d\'exploiter un délai pour l\'envoi de courrier interne',
    'adm_set_acl_vote_blog_rating' => 'Le taux de seuil à partir duquel l\'utilisateur peut voter pour le blog',
    'adm_set_acl_vote_topic_rating' => 'Le taux de seuil à partir duquel l\'utilisateur peut voter pour le sujet',
    'adm_set_acl_vote_comment_rating' => 'Le taux de seuil à partir duquel l\'utilisateur peut voter pour les commentaires',
    'adm_set_acl_vote_user_rating' => 'Le taux de seuil à partir duquel l\'utilisateur peut voter pour les utilisateurs',
    'adm_set_acl_vote_topic_limit_time' => 'Limiter le temps de voter pour le sujet (s)',
    'adm_set_acl_vote_comment_limit_time' => 'Limiter le temps de voter pour le commentaire (s)',

);
// EOF