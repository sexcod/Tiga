<?php

namespace Blog;

use Resource\Main as Main;
use Lib;
use Config;


class Home extends Main
{
	public $twig = true; // Enable Twig


	function index()
	{
		$title = 'My Webpage';		
		$a_variable = "Welcome my web page!"; 
		
		//NeosTags template engine -------------------------------------------		
		/* uncomment this to enable ©NeosTags
		$navigation = ['<a href="'._URL.'">Home</a>',
		               '<a href="'._URL.'articles">Articles</a>',
		               '<a href="'._URL.'contact">Contacts</a>'];
		
		(new \Lib\Html)->render('ntag', ['title'=>$title,
										 'navigation'=>$navigation, 
							             'a_variable'=>$a_variable])
					   ->send();
	    */
		
		//Twig template engine -----------------------------------------------
		$navigation = [['href'=>_URL,'caption'=>'Home'],
					   ['href'=>_URL.'articles','caption'=>'Articles'],
					   ['href'=>_URL.'contact','caption'=>'Contacts'],
						];

		echo $this->twig
				  ->render('twig.html', 
								['title'=>$title,
								 'navigation'=>$navigation, 
								 'a_variable'=>$a_variable
								]);
	}
}