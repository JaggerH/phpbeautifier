# phpbeautifier

This repository is from Sublime Text 3 - CodeFormatter.
The PHP-Beautifier fix the Bug in official repository.
Official repository have problem to format array in square brackets.

----------------------------------------------------------
------------------       Example        ------------------ 
----------------------------------------------------------

The array before format is:

[
    'access' => [
        'class' => AccessControl::className(),
        'only' => ['logout'],
        'rules' => [
            [
                'actions' => ['logout'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
            'logout' => ['post'],
        ],
    ],
];

After format:
['access' => ['class' => AccessControl::className() , 'only' => ['logout'], 'rules' => [['actions' => ['logout'], 'allow' => true, 'roles' => ['@'], ], ], ], 'verbs' => ['class' => VerbFilter::className() , 'actions' => ['logout' => ['post'], ], ], ];

It's  hard to read.
I fix The Problem, you can clone the repository under the path:

    ~/Library/Application Support/Sublime Text 3/Packages/CodeFormatter/codeformatter/lib/

But in Sublime Text 3, you need set a field in CodeFormatter.sublime-settings

    "space_in_square": true

Beacuse i directly Change the Code in space_in_square code segment.

I'm lazy, hoho~
