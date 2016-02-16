# Smoke

smoke is a amovible, flexible PHP micro framework for rapid web development and lightweight application.
Smoke is easy to learn and provides everything that you can expect from a modern framework (MVC, REST, ...)


## Requirements ##

* PHP 5.1.6 > (successfully tested with PHP 5.1.6 but it might work with older versions)
  
## Routes ##

Routes combine 

* an HTTP method 
* with an URL matching pattern 
* and a callback parameter

    get('/', 'get_function');
    function get_function()
    {
        // Show something
        // with the code of this callback controller
    }

    post('/', 'post_function');
    function post_function()
    {
        // Show something
        // with the code of this callback controller
    }

    put('/', 'put_function');
    function put_function()
    {
        // Show something
        // with the code of this callback controller
    }

### Routing patterns and parameters ###

* matching numeric parameters

    get('/post/:num', 'post');
    function post($num)
    {
        echo "this is post $num";
    }

* matching alphanumeric parameters

    get('/post/:alpha', 'post');
    function post($var)
    {
        echo "this is post $var";
    }

* matching any format of parameters

    get('/post/:any', 'post');
    function post($var)
    {
        echo "this is post $var";
    }

## Views and templates ##

Views files are located by default in `/app/templates/` folder.


* display a view

    get('/about', 'about');
    function about()
    {
        view('apropos',[],'default');
    }

> first parameter   : name of views

> second parameters : parameter 

> third parameter   : the layout

* The layout 

Layout files are located by default in `/app/templates/layouts/` folder.

    <!DOCTYPE html>
    <html>
    <head>
        <title>Layout</title>
    </head>
    <body>
    <?= $content ?>
    </body>
    </html>

## SESSION AND FLASH ##

* Create a session var

    session('create','key','value');

* Get a session var 

    session('get','key');

* Delete a session var

    session('delete','key');

* Update a session var

    session('put','key','new value');

### FLASH MESSAGE ###

Flash files are located by default in `/app/templates/flash/` folder.
* Create a flash
flash_create('Well done !!','success');

* Display flash

	** html file ***
	<span style="background:green; color: white;">
		<?= $render; ?>
	</span>
	** php render **

 echo flash_display();

### HTML HELPER ###

* img

    echo img('image1.jpg',['style' => 'border: solid 1px red;']);

* link

    echo url('about','about page' , ['class' => 'link_red']);

* css 
    
    echo css('bootstrap.css');

or

    echo css(['bootstrap.css' , 'style.css']);

* js 

    echo js('bootstrap.js');

or

    echo js(['bootstrap.js' , 'style.js']);