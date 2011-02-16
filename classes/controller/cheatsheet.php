<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana cheat sheet browser.
 *
 * @author     Alexander Kupreev (http://kupreev.com, alexander dot kupreev at gmail dot com)
 * @version    0.4
 */
class Controller_Cheatsheet extends Controller_Template {

    public $template = 'cheatsheet/template';

    public $ext = '.cs';

    /**
     * modified from userguide module controller
     */
    public function before()
    {
	if ($this->request->action === 'media')
	{
        // Do not template media files
        $this->auto_render = FALSE;
	}
	else
	{

        // Use customized Markdown parser
        define('MARKDOWN_PARSER_CLASS', 'Kodoc_Markdown');

        // Load Markdown support
        require Kohana::find_file('vendor', 'markdown/markdown');
        
	}

	    parent::before();
    }


    public function action_index()
    {
        $msg = array();

        $exclude_class = array_map(
            'strtolower', 
            Kohana::config('cs.exclude_class')
            );
        
        // arrays of different mask types and precise names
        $prec = array(); // like 'someword'
        $ast_left = array(); // like '*someword' 
        $ast_right = array(); // like 'someword*'
        $two_ast = array(); // like '*someword*'  
        
        foreach ($exclude_class as $mask)
        {
            
            if (strpos($mask, '*') === 0 AND (strrpos($mask, '*') === strlen($mask) - 1))
            {
                // any occurrence
                $two_ast[] = substr($mask, 1, -1);
            }
            elseif (strpos($mask, '*') === 0)
            {
                // masked as '*someword'
                $ast_left[] = substr($mask, 1);
            } 
            elseif (strrpos($mask, '*') === strlen($mask) - 1)
            {
                // masked as 'someword*'
                $ast_right[] = substr($mask, 0, -1);
            }
            else
            {
                $prec[] = $mask;
            }                                     
        }
        
        $classes = Kodoc::classes();

        // remove excluded classes from list
        foreach ($classes as $class)
        {
            if (isset($classes['kohana_'.$class]))
            {
                unset($classes['kohana_'.$class]);
            }
            
            // exclude classes that have names set precisely            
            if (in_array(strtolower($class), $prec))
            {
                unset($classes[$class]);
                continue;
            }
            
            $is_class_unset = FALSE;
            
            // exclude classes that have names set by mask of type '*someword*'
            foreach ($two_ast as $mask)
            {
                if (strpos(strtolower($class), $mask) !== FALSE)
                {
                    unset($classes[$class]);
                    $is_class_unset = TRUE;
                    break;
                }                    
            }
            
            if ($is_class_unset)
            {
                continue;
            }
            
            // exclude classes that have names set by mask of type '*someword'
            foreach ($ast_left as $mask)
            {
                if (substr(strtolower($class), -strlen($mask)) == $mask)
                {
                    unset($classes[$class]);
                    $is_class_unset = TRUE;
                    break;                    
                }                    
            }
            
            if ($is_class_unset)
            {
                continue;
            }
            
            // exclude classes that have names set by mask of type 'someword*'
            foreach ($ast_right as $mask)
            {
                if (strpos(strtolower($class), $mask) === 0)
                {
                    unset($classes[$class]);
                    break;
                }                    
            }
        }

        $is_cache = Kohana::config('cs.cache');

        // do we need to save actual state in cache
        $is_save_cache = FALSE;
        // check if caching turned on
        if ($is_cache)
        {
            // check whether classes set + exclude classes set was modified
            $cache_name = sha1(serialize($classes)).$this->ext;

            $dir = Kohana_Core::$cache_dir.DIRECTORY_SEPARATOR;

            if ( ! is_dir($dir))
            {
                $msg[] = 'No cache directory '.$dir;
                $is_save_cache = TRUE;
            } 
            else 
            {

                if (is_file($dir.$cache_name))
                {
                    $tmp = file_get_contents($dir.$cache_name);
                    if ($tmp)
                    {
                        $classes = unserialize($tmp);
                        $msg[] = 'Data loaded from cache';
                    } else {

                        $is_save_cache = TRUE; // set for data not be taken from cache
                        $msg[] = 'Failed to load cache';
                    }

                } 
                else 
                {

                    $is_save_cache = TRUE;

                    foreach (glob($dir.'*'.$this->ext) as $filename)
                    {
                        if ( ! unlink($filename))
                        {
                            $msg[] = 'Can not delete cache file '.$filename;
                        }
                    }
                }
            }
        } 
        
        if ( ! $is_cache OR $is_save_cache)
        {
            
            foreach ($classes as $class)
            {
                $r_class = Kodoc_Class::factory($class);
                
                // to prevent exception when Kodoc::properties() throws exception
                try
                {
                    $props = $r_class->properties();
                } catch (Kohana_Exception $e) {
                    $props = array();

                    $msg[] = $e->getMessage();
                }

                $classes[$class] = array(
                	'description' => $r_class->description,
                	'modifiers'	=> $r_class->modifiers,
                    'properties' => $props,
                    'methods' => $r_class->methods(),
                    );              
            }

            if ($is_save_cache)
            {
                if (is_dir($dir) AND is_writable($dir))
                {
                    if ( ! file_put_contents($dir.$cache_name, serialize($classes)))
                    {
                        $msg[] = 'Failed to save cache';
                    }
                } else {
                    $msg[] = 'Not exsisting or not writable cache dir';
                }
            }
        }

        $this->template->content = $classes;

        $this->template->msg = $msg;
    }
    
    public function action_invalidcache()
    {
        $dir = Kohana_Core::$cache_dir.DIRECTORY_SEPARATOR;

        if (is_dir($dir))
        {
        
            $filelist = glob($dir.'*'.$this->ext);
            
            foreach ($filelist as $filename)
            {
                @unlink($filename);
            }
        }
        
        $this->request->redirect('cs');
    }

    /**
     * taken from userguide module controller
     */
    public function action_media()
    {
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media', $file, $ext))
		{
			$this->request->check_cache(sha1($this->request->uri).filemtime($file)); 

			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		}
		else
		{
		// Return a 404 status
			$this->request->status = 404;
		}

		$this->request->headers['Content-Type']   = File::mime_by_ext($ext);
		$this->request->headers['Content-Length'] = filesize($file);
		$this->request->headers['Last-Modified']  = date('r', filemtime($file));
	}

    /**
     * modified from userguide module controller
     */
    public function after()
    {
	    if ($this->auto_render)
	    {
			// Get the media route
			$media = Route::get('cheatsheet');

			// Add styles
			$this->template->styles = array(
				$media->uri(array('action' => 'media', 'file' => 'css/cs.css')) => 'screen',
				);

			// Add scripts
			$this->template->scripts = array(
				$media->uri(array('action' => 'media', 'file' => 'js/jquery-1.4.4.min.js')),
				$media->uri(array('action' => 'media', 'file' => 'js/jquery.columnizer.min.js')),
				$media->uri(array('action' => 'media', 'file' => 'js/cs.js')),
				);
            
        }

	    return parent::after();
    }
    
} 