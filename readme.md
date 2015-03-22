composer.json

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ivanmijatovic89/laravel-proto-generator"
        }
    ],
    "require-dev": {
        "dam1r89/proto-generator": "dev-master"
    },

add to `app/config/app.php`

	'providers' => [
	    ...
        'dam1r89\ProtoGenerator\ProtoGeneratorServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
    ]

    'aliases' =>
    [
        ...
        'Form' => 'Illuminate\Html\FormFacade',
        'Html' => 'Illuminate\Html\HtmlFacade',
    ]


##Example

    php artisan proto post --fields='{"name":{}, "body":{}}'

##Flags

*-r* replace files withouth asking  
*-t* template folder - default `standard`  
*-f* fields  
*-o* output folder - default base

