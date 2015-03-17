<?php	/*		Awesome New Templates	*/		require_once __DIR__ . DIRECTORY_SEPARATOR . 'ant' . DIRECTORY_SEPARATOR . 'ant.parser.php';	require_once __DIR__ . DIRECTORY_SEPARATOR . 'ant' . DIRECTORY_SEPARATOR . 'ant.helper.php';	require_once __DIR__ . DIRECTORY_SEPARATOR . 'ant' . DIRECTORY_SEPARATOR . 'ant.io.php';	require_once __DIR__ . DIRECTORY_SEPARATOR . 'ant' . DIRECTORY_SEPARATOR . 'ant.fn.php';	require_once __DIR__ . DIRECTORY_SEPARATOR . 'ant' . DIRECTORY_SEPARATOR . 'ant.cache.php';	require_once __DIR__ . DIRECTORY_SEPARATOR . 'ant' . DIRECTORY_SEPARATOR . 'ant.exception.php';	class Ant	{		const MODE_FILE   = 0xFF;		const MODE_STRING = 0x00;		private static $cache_obj = null;		private static $settings = array();		private $mode = null;		private $assign      = array();		private $tmpl_path   = "";		private $cache_path  = "";		private $string      = "";		public static function init()		{			return new self();		}		public function setup($s)		{			if(false == isset($s['view']))				throw new Ant\AntException('View path is not defined');						if(false == @is_readable($s['view']))				throw new Ant\AntException('View path ' . $s['view'] . ' is not available');							if(false == isset($s['cache']))				throw new Ant\AntException('Cache path is not defined');			if(false == @is_readable($s['cache']) or false == @is_writeable($s['cache']))				throw new Ant\AntException('Cache path ' . $s['cache'] . ' is not available');			self::$settings = $s;			self::$cache_obj = new Ant\Cache($s['cache']);			return $this;		}		public static function settings($name = false)		{				return $name != false ? self::$settings[$name] : self::$settings;		}		public static function __callStatic($name, $arguments)		{			if(method_exists('Ant\Fn',$name))				return call_user_func_array("Ant\Fn::{$name}",$arguments);			else				throw new Ant\AntException("Undeclared method 'Ant::{$name}'");		}		public function get($path)		{			$this->mode = self::MODE_FILE;			$this->tmpl_path  = self::$settings['view'] . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path) . '.php';						if(false == file_exists($this->tmpl_path))				throw new Ant\AntException('Template file not found at ' . $this->tmpl_path);			$this->cache_path = self::$settings['cache'] . DIRECTORY_SEPARATOR . $path . '.php';			return $this;		}		public function fromString($s)		{			$this->mode = self::MODE_STRING;			$this->string = $s;			return $this;		}		public function assign(array $data = array())		{			$this->assign = $data;			return $this;		}		public function draw()		{			switch($this->mode){				case self::MODE_STRING:					$s = Ant\Parser::parse($this->string);					ob_start();					extract($this->assign);					eval(' ?>' . $s . '<?php ');					$echo = ob_get_contents();					ob_end_clean();					return $echo;				break;				case self::MODE_FILE:					if(false == self::$cache_obj->check($this->tmpl_path) or false == file_exists($this->cache_path)){						$io = Ant\IO::init()->in($this->tmpl_path);												$cnt = $io->get();						$cnt = Ant\Parser::parse($cnt);						$io->out()						->in($this->cache_path)						->set($cnt)						->out();					}					ob_start();					extract($this->assign);					include $this->cache_path;					$echo = ob_get_contents();					ob_end_clean();					return $echo;				break;			}		}	}?>