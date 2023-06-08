<?php

$Module = [
    'name' => 'iText SMS',
    'variable_params' => true,
];

$ViewList = [];

$ViewList['callbacks'] = [
    'params' => [],
    'uparams' => [],
];

$ViewList['sendsms'] = [
    'params' => [],
    'uparams' => [],
    'functions' => ['use'],
];

$ViewList['list'] = [
    'params' => [],
    'uparams' => [],
    'functions' => ['use_admin'],
];

$ViewList['new'] = [
    'params' => [],
    'uparams' => [],
    'functions' => ['use_admin'],
];

$ViewList['edit'] = [
    'params' => ['id'],
    'uparams' => [],
    'functions' => ['use_admin'],
];

$ViewList['delete'] = [
    'params' => ['id'],
    'uparams' => ['csfr'],
    'functions' => ['use_admin'],
];

$ViewList['index'] = [
    'params' => [],
    'functions' => ['use_admin'],
];

$FunctionList['use'] = ['explain' => 'Allow operator to send SMS directly'];
$FunctionList['use_admin'] = ['explain' => 'Allow operator to add phone number to department'];
