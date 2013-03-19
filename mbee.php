<?php 
class Mbee {

	public static function make($mod){

		$modFile = MBEE_SCRIPTS_DIR.$mod.'.js';
		$commondParam = 'mg';

		//debug模式
		if(MBEE_DEBUG)
			$commondParam .='d';

		if(extension_loaded('zlib')){
			ob_start('ob_gzhandler');
		}

		header ("content-type: application/x-javascript; charset: utf-8");
		header ("cache-control: public");

		$offset = 60 * 60 * 240;
		$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
		header ($expire);

		// echo "$modFile";
		if(file_exists($modFile)){
			exec('cd '.MBEE_SCRIPTS_DIR.';node deploy.js -'.$commondParam.' '.$mod.'.js',$ret);
			$exitMod = MBEE_SCRIPTS_DIR.$mod.'.mod.js';

			//拼接返回结果
			$ret = implode('', $ret);
			
			if(file_exists($exitMod) && file_get_contents($exitMod) === $ret){
				$last_modified = filemtime($exitMod);
				$is304 = self::caching_headers($modFile,$last_modified);
				
				if(!$is304)
					echo $ret;
			}else{
				file_put_contents($exitMod, $ret);
				$last_modified = filemtime($exitMod);
				self::caching_headers($modFile,$last_modified);
				echo($ret);
			}
		}
		
		exit();
	}

	private static function caching_headers($file,$timestamp){
	    $gmt_mtime=gmdate('r', $timestamp);
	    header('ETag: '.md5($timestamp.$file));
	    if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])||isset($_SERVER['HTTP_IF_NONE_MATCH'])){
	        if ($_SERVER['HTTP_IF_MODIFIED_SINCE']==$gmt_mtime||str_replace('"','',stripslashes($_SERVER['HTTP_IF_NONE_MATCH']))==md5($timestamp.$file)){
	            header('HTTP/1.1 304 Not Modified');
	            return true;
	        }else{
	        	return false;
	        }
	    }
	    header('Last-Modified: '.$gmt_mtime);
	    header('Cache-Control: public');
	    return false;
	}
}