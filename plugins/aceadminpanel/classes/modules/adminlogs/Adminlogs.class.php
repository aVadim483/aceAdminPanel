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

/***
 * Отложенная запись
 */
class AdminLogs_Record extends Object {
	protected $nTime;
	protected $nLines=0;
	protected $sBuffer='';
	
	public function __construct($nTime=0) {
		$this->Init($nTime);
	}
	
	public function Init($nTime=0) {
		if (!$nTime) $this->nTime = microtime(true);
		else $this->nTime = $nTime;
		$this->sBuffer = '['.date('Y-m-d H:i:s').'] ';
		$this->nLines=0;
	}
	
	public function AddLine($sLine='') {
		if ($this->nLines) $sLine = str_repeat(' ', 22).$sLine;
		$this->sBuffer .= $sLine."\n";
		$this->nLines += 1;
	}
	
	public function Add() {
		for ($i=0; $i<func_num_args(); $i++) {
			$arg = func_get_arg($i);
			if (is_array($arg)) {
				foreach ($arg as $key=>$val) {
					$this->AddLine($key.'=>"'.$val.'"');
				}
			} else {
				$this->AddLine($arg);
			}
		}
	}
	
	public function Clear() {
		$this->nTime = 0;
		$this->nLines=0;
		$this->sBuffer='';
	}
	
	public function Get($bElapsedTime=false) {
		if (!$this->nTime) return '';
		$result = $this->sBuffer;
		if ($bElapsedTime) {
			$result .= str_repeat(' ', 22).'time=>"'.sprintf('%01.4f', microtime(true)-$this->nTime);
		}
		return $result;
	}
	
	public function End($bElapsedTime=false) {
		if (!$this->nTime) return '';
		$result = $this->Get($bElapsedTime)."\n";
		$this->Clear();
		return $result;
	}

	public function Make() {
		$this->Init();
		for ($i=0; $i<func_num_args(); $i++) {
			$arg = func_get_arg($i);
			$this->Add($arg);
		}
		$this->End();
	}
	
}

/**
 * Модуль логгирования, позволяющий вести параллельно несколько лог-файлов
 */
class LsAdminLogs extends Module {		
	protected $sPathLogs=null;			// Путь до каталога с логами
	protected $aFileLogs=array();		// Лог-файлы: имя файла, флаг on/off
	protected $aLogDefault=array('file'=>'', 'mode'=>1, 'trace'=>false, 'record'=>null);
	protected $aRecords=array();
	
	protected $nOptionMaxSize=LOGS_MAX_SIZE;	// Максимальный размер лог-файла; если = 0, то ротация по дате
	protected $nOptionMaxFiles=LOGS_MAX_FILES;	// Максимальное число лог-файлов

	/**
	 * Инициализация модуля
	 */
	public function Init() {		
		$this->sPathLogs=Config::Get('path.root.server').'/logs/';
	}

	/**
	 * Создание нового индекса лог-файла
	 *
	 * @return string
	 */
	public function GetNewLogIndex() {
		do {
			$sLogIndex = time().'-'.round(rand(100, 999));
		} while (!isset($this->aFileLogs[$sLogIndex]));
		
		$this->aFileLogs[$sLogIndex] = $aLogDefault;
		return $sLogIndex;
	}

	/**
	 * Устанавливает имя файла лога
	 *
	 * @param mix			$sLogIndex		- индекс лог-файла
	 * @param string 	$sFile				- имя лог-файла
	 *
	 * @return integer
	 */
	public function SetLogFile($sLogIndex, $sFile){
		if (!$sLogIndex) {$sLogIndex=$this->GetNewLogIndex();}
		$this->aFileLogs[$sLogIndex]['file']=(string)$sFile;
		return $sLogIndex;
	}
	
	/**
	 * Устанавливает режим вывода лога
	 *
	 * @param mix			$sLogIndex		- индекс лог-файла
	 * @param integer	$nMode
	 *
	 * @return integer
	 */
	public function SetLogMode($sLogIndex, $nMode){
		if (!$sLogIndex) {$sLogIndex=$this->GetNewLogIndex();}
		$this->aFileLogs[$sLogIndex]['mode']=$nMode?1:0;
		return $sLogIndex;
	}

	/**
	 * Устанавливает трассировку лога
	 * 		true - к каждой записи добавляет стек вызовов
	 *
	 * @param mix			$sLogIndex		- индекс лог-файла
	 * @param boolean $bTrace
	 *
	 * @return integer
	 */
	public function SetLogTrace($sLogIndex, $bTrace){
		if (!$sLogIndex) {$sLogIndex=$this->GetNewLogIndex();}
		$this->aFileLogs[$sLogIndex]['trace']=(boolean)$bTrace;
		return $sLogIndex;
	}


	/**
	 * Устанавливает параметры лога
	 *
	 * @param string $sLogIndex
	 * @param array $aOptions
	 *
	 * @return integer
	 */
	public function SetLogOptions($sLogIndex, $aOptions=array()){
		if (!$sLogIndex) {$sLogIndex=$this->GetNewLogIndex();}
		if (!isset($this->aFileLogs[$sLogIndex])) $this->aFileLogs[$sLogIndex]=$this->aLogDefault;
		if (is_array($aOptions) && $aOptions) {
			foreach ($aOptions as $key=>$val) {
				if ($key=='file') $this->SetLogFile($sLogIndex, $val);
				elseif ($key=='mode') $this->SetLogMode($sLogIndex, $val);
				elseif ($key=='trace') $this->SetLogTrace($sLogIndex, $val);
			}
		}
		return $sLogIndex;
	}

	/**
	 * Получает имя файла лога
	 *
	 * @param string $sLogIndex
	 *
	 * @return string
	 */
	public function GetLogFile($sLogIndex){
		if ($sLogIndex && isset($this->aFileLogs[$sLogIndex]) && isset($this->aFileLogs[$sLogIndex]['file']))
			return $this->aFileLogs[$sLogIndex]['file'];
		else 
			return false;
	}
	
	/**
	 * Получает режим вывода лога
	 *
	 * @param string $sLogIndex
	 *
	 * @return integer
	 */
	public function GetLogMode($sLogIndex){
		if ($sLogIndex && isset($this->aFileLogs[$sLogIndex]) && isset($this->aFileLogs[$sLogIndex]['mode']))
			return $this->aFileLogs[$sLogIndex]['mode'];
		else 
			return false;
	}
	
	/**
	 * Получает режим трассировки лога
	 *
	 * @param string $sLogIndex
	 *
	 * @return boolean
	 */
	public function GetLogTrace($sLogIndex){
		if ($sLogIndex && isset($this->aFileLogs[$sLogIndex]) && isset($this->aFileLogs[$sLogIndex]['trace']))
			return $this->aFileLogs[$sLogIndex]['trace'];
		else 
			return false;
	}
	
	/**
	 * Получает параметры лога

	 *
	 * @param string $sLogIndex
	 *
	 * @return string
	 */
	public function GetLogOptions($sLogIndex){
		if ($sLogIndex && isset($this->aFileLogs[$sLogIndex]) && isset($this->aFileLogs[$sLogIndex]))
			return $this->aFileLogs[$sLogIndex];
		else 
			return false;
	}
	
	public function GetLogs($sLogIndex=null, $aOptions=array()) {
		if (!is_null($sLogIndex)) $this->SetLogOptions($sLogIndex, $aOptions);
		return $this;
	}
	
	/**
	 * Получает полное имя файла лога
	 *
	 * @param string $sLogIndex
	 *
	 * @return string
	 */
	public function GetFileName($sLogIndex){
		if ($sFileName=$this->GetLogFile($sLogIndex)) {
			return $this->sPathLogs.$sFileName;
		} else {
			return '';
		}
	}

	/**
	 * Выполняет форматирование трассировки
	 *
	 * @param array $aTrace
	 * @return string
	 */
	protected function ParserTrace($aTrace) {
		$str='';
		for ($i=count($aTrace)-1;$i>=0;$i--) {
			$str.=str_repeat(' ', 14).'[trace] ';
			if (isset($aTrace[$i]['class'])) {
				$funct=$aTrace[$i]['class'].$aTrace[$i]['type'].$aTrace[$i]['function'].'()';
			} else {
				$funct=$aTrace[$i]['function'].'()';
			}
			if (isset($aTrace[$i]['file'])) $str.=$aTrace[$i]['file'].'('.$aTrace[$i]['line'].')'."\n".str_repeat(' ', 22).$funct;
			else $str.='{'.$funct.'}';
			$str.="\n";
		}
		return $str;
	}

	/**
	 * Записывает строку в лог
	 *
	 * @param string $sStr
	 */
	public function Out($sLogIndex, $sStr) {
		
		/**
		 * Формируем текст лога
		 */
		$msgOut ='['.date("Y-m-d H:i:s").']';
		$msgOut.=' '.$sStr;
		/**
		 * Если нужно то трассируем
		 */
		if ($this->GetLogTrace($sLogIndex)) {				
			$msgOut.="\n".$this->ParserTrace(debug_backtrace())."\n";
		}
		/**
		 * Записываем
		 */
		return $this->Write($sLogIndex, $msgOut);
	}

	/**
	 * Переименовывает все файлы логов согласно их последовательности
	 *
	 * @param int $nNumberLast
	 */
	protected function RotateRename($sLogIndex, $nNumberLast) {
		$pathinfo=pathinfo($this->sPathLogs.$this->getLogFile($sLogIndex));
		$aName=explode('.',$pathinfo['basename']);		
		for ($i=$nNumberLast;$i>0;$i--) {
			$sFullNameCur=$pathinfo['dirname'].'/'.$aName[0].".$i.".$aName[1];
			$sFullNameNew=$pathinfo['dirname'].'/'.$aName[0].'.'.($i+1).'.'.$aName[1];
			@unlink($sFullNameNew);
			@rename($sFullNameCur, $sFullNameNew);
		}
		$sFullNameNew=$pathinfo['dirname'].'/'.$aName[0].".1.".$aName[1];
		@unlink($sFullNameNew);
		@rename($this->sPathLogs.$this->getLogFile($sLogIndex), $sFullNameNew);
	}

	protected function RotateBySize($sLogIndex, $sFileName) {
		$pathinfo=pathinfo($sFileName);			
		$name=$pathinfo['basename'];
		$aName=explode('.',$name);
		$i=1;
		for ($i=1;$i<$this->nOptionMaxFiles;$i++) {
			$sNameNew=$aName[0].".$i.".$aName[1];
			$sFullNameNew=$pathinfo['dirname'].'/'.$sNameNew;
			if (!file_exists($sFullNameNew) || $i==$this->nOptionMaxFiles-1) {
				$this->RotateRename($sLogIndex, $i-1);
				break;
			}				
		}			
	}
	
	/**
	 * Производит сохранение в файл
	 *
	 * @param string $sLogIndex		- индекс лог-файла
	 * @param string $sStr				- выводимая строка
	 *
	 * @return boolean
	 */
	protected function Write($sLogIndex, $sStr) {
		$result=false;
		/**
		 * Если имя файла не задано то ничего не делаем
		 */
		if (!$this->getLogFile($sLogIndex) or !$this->getLogMode($sLogIndex)) {
			return false;
		}
		/**
		 * Если имя файла равно '-' то выводим сообщение лога на экран(браузер)
		 */
		if ($this->getLogFile($sLogIndex)=='-') {
			echo('***'.$sStr."<br>\n");
		} else {
			$sFileName=$this->getFileName($sLogIndex);

			/******************************************************
			 * Безопасная запись в файл с использованием блокировки файла-семафора
			 */
			// Для надежности используем дополнительный файл-семафор
			$sFileLock=$sFileName.'.lock';
			// Создаем пустой файл-семафор, если его еще нет
			if ($fl=@fopen($sFileLock, "a+b")) @fclose($fl);

			// Блокируем файл-семафор
			if (!($fl = @fopen($sFileLock, "r+b"))) return false;
			flock($fl, LOCK_EX); // ждем, пока мы не станем единственными
			// В этой точке мы можем быть уверены, что только эта
			// программа работает с файлом.

			if (!$this->nOptionMaxSize && file_exists($sFileName) && 
			    date('Y-m-d')>date('Y-m-d', filemtime($sFileName))) {
				$this->RotateBySize($sLogIndex, $sFileName);
			}
			if ($fp=fopen($sFileName, 'a')) {
    		$result=fwrite($fp, $sStr."\n");
    		fclose($fp);    	
    		/**
    		 * Если нормально записалось, то проверяем, не нужно ли делать ротацию
    		 */
				if ($this->nOptionMaxSize && $result && 
					  filesize($sFileName)>=$this->nOptionMaxSize) {
					$this->RotateBySize($sLogIndex, $sFileName);
				}
			}
			// Все сделано. Снимаем блокировку.
			fclose($fl);
			/*
			 ****************************************************/
		}
		return (boolean)$result;
	}

	//+++++++++++++++++++++
	// records
	public function RecordInit($sLogIndex) {
		$this->aFileLogs[$sLogIndex]['record'] = new AdminLogs_Record();
		return $this->aFileLogs[$sLogIndex]['record'];
	}

	public function RecordClear($sLogIndex) {
		if (!isset($this->aFileLogs[$sLogIndex])) return false;

		$this->aFileLogs[$sLogIndex]['record'] = null;
		return true;
	}

	protected function GetRecord($sLogIndex) {
		if (!isset($this->aFileLogs[$sLogIndex])) return false;

		$oRecord = $this->aFileLogs[$sLogIndex]['record'];
		return $oRecord;
	}
	
	public function RecordAdd($sLogIndex, $xValue) {
		if (!isset($this->aFileLogs[$sLogIndex])) return false;

		$result = false;
		if (!$this->GetRecord($sLogIndex)) $this->RecordInit($sLogIndex);
		if ($oRecord = $this->GetRecord($sLogIndex)) {
			$oRecord->Add($xValue);
			$result = true;
		}
		return $result;
	}

	public function RecordEnd($sLogIndex, $bElapsedTime=false) {
		if (!isset($this->aFileLogs[$sLogIndex])) return false;

		if ($oRecord = $this->GetRecord($sLogIndex)) {
			$sRecord = $oRecord->End($bElapsedTime);
			$this->Write($sLogIndex, $sRecord);
			$this->RecordClear($sLogIndex);
			return true;
		}
		return false;
	}
	
	public function RecordOut($sLogIndex, $sValue, $bElapsedTime=false) {
		if (!isset($this->aFileLogs[$sLogIndex])) return false;

		$this->RecordInit($sLogIndex);
		$this->RecordAddLine($sLogIndex, $sValue);
		$this->RecordEnd($sLogIndex, $bElapsedTime);
		return true;
	}

	public function Shutdown() {
		foreach ($this->aFileLogs as $sLogIndex=>$aLog) {
			if ($aLog['record']) {
				$this->RecordEnd($sLogIndex);
			}
		}
	}

}

 // EOF