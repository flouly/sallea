
<?php
function debug($d, $mode = 1)
{
	echo '<div style="background: orange; padding: 5px; z-index: 1000;">';
	$trace = debug_backtrace();
	echo 'debug demandé dans le fichier ' . $trace[0]['file'] . ' à la ligne ' . $trace[0]['line'];
	// echo '<pre>'; print_r($trace); echo '</pre>';
		if($mode == 1) 
		{
			echo '<pre>'; print_r($d); echo '</pre>';
		}
		else
		{
			var_dump($d);
		}
	echo '</div>';
}

function dd($d, $mode = 1)
{
	echo '<div style="background: orange; padding: 5px; z-index: 1000;">';
	$trace = debug_backtrace();
	echo 'debug demandé dans le fichier ' . $trace[0]['file'] . ' à la ligne ' . $trace[0]['line'];
	// echo '<pre>'; print_r($trace); echo '</pre>';
		if($mode == 1) 
		{
			echo '<pre>'; print_r($d); echo '</pre>';
		}
		else
		{
			var_dump($d);
		}
	echo '</div>';
    die();
}