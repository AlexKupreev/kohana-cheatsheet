<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title>Kohana <?php echo Kohana::VERSION; ?> CheatSheet Generator</title>
<?php 
foreach ($styles as $style => $media)
{
    echo HTML::style($style, array('media' => $media), TRUE);
}
?>
</head>
<body>
    <h2>Kohana <?php echo Kohana::VERSION.' ('.Kohana::CODENAME.')';?> CheatSheet</h2>
    <div class="des"><p>Designation: <span class="static">&lt;item&gt;</span> &mdash; static property/method; #&nbsp;&lt;item&gt; &mdash; protected property/method</p></div>
    
<div id="cs">
<?php   
foreach ($content as $name=>$stuff)
{   
?>
    <div class="ko3class">
        <div class="classname"><?php echo $name;?></div>
        <?php
    $close_btn = '<div class="closebtn">close</div>';
                    
    if ( ! empty($stuff['properties']))
    {
?>
            <div class="capt">properties</div>
<?php
        foreach ($stuff['properties'] as $prop)
        {
            $add = '<div class="tooltip">'.$close_btn.'<h4>'.$prop->property->class.'::'.$prop->property->name.'</h4>'.$prop->description.'</div>';
            $pr = '';
            if (stripos($prop->modifiers,'protected') !== FALSE)
            {
                $pr = '#&nbsp;';
            }
            
            if (stripos($prop->modifiers,'static') !== FALSE)
            {
?>
            <div class="item"><span class="static"><?php echo $pr.$prop->property->name; ?></span><?php echo $add;?></div>
<?php       
            } 
            else 
            {
?>
            <div class="item"><span class="nostatic"><?php echo $pr.$prop->property->name; ?></span><?php echo $add; ?></div>
<?php
            }
                                        
        }
    }
                
    if ( ! empty($stuff['methods']))
    {
?> <div class="capt">methods</div>
<?php
        foreach ($stuff['methods'] as $me)
        {
            if (stripos($me->method->name,'__') === FALSE)
            {
                $params = '';
                if (is_array($me->params) AND ! empty($me->params))
                {
                    $params = array();
                    foreach ($me->params as $p)
                    {
                        $defaults = $p->default ? ' = '.$p->default : '';
                        $params[] = '<small>'.$p->type.'</small> $'.$p->name.$defaults; 
                    }
                                
                    $params = implode(', ', $params); 
                }
                            
                $add = '<div class="tooltip">'.$close_btn.'<h4>'.$me->method->class.'::'.$me->method->name.'('.$params.')</h4>'.$me->description.'</div>';
                $pr = '';
                if (stripos($me->modifiers,'protected') !== FALSE)
                {
                    $pr = '#&nbsp;';
                }
                if (stripos($me->modifiers,'static') !== FALSE)
                {
                    echo '<div class="item"><span class="static">'.$pr.$me->method->name.'</span>'.$add.'</div>';
                } 
                else 
                {
                    echo '<div class="item"><span class="nostatic">'.$pr.$me->method->name.'</span>'.$add.'</div>';
                }
                            
            }
                            
        }
    }
?>
    </div>
<?php   
} 
?>
</div>
<?php
foreach ($scripts as $script)
{
    echo HTML::script($script, NULL, TRUE);
}

$stats = Profiler::application();
?>
    <div class="des stats">Execution time: <?php echo number_format($stats['current']['time'], 3), ' ', __('seconds');?> &mdash;
        Memory: <?php echo ceil($stats['current']['memory']/1024).' kb'; ?>
        <?php echo (empty ($msg)) ? '' : '<br />'.implode('<br />',$msg);?><br />
        <?php echo HTML::anchor('cs/invalidcache', 'Invalidate cache'); ?>
    </div>
</body>
</html>
