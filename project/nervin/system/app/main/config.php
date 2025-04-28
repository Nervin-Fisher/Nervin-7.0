<?php
$debug = TRUE;
$status = "prod";
$config = [
    "prod" => [
        "hosts" => [
            "allowed" => ["*"],
            "blocked" => []
        ],
        "csrf" => [
            "allowed" => ["*"],
            "blocked" => []
        ],
        "db" => [
            "MAIN_DB" => [
                "TYPE" => "mysql",
                "NAME" => "db1",
                "USER" => "root",
                "PASSWORD" => "",
                "HOST" => "mysql",
                "PORT" => "",
                "CHARSET" => "utf8"
            ]
        ]
    ]
];
