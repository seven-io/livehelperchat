<?php
$doSearch = isset($_GET['doSearch']);
$searchParams = [
    'customfilterfile' => 'extension/sms77/classes/filter.php',
    'format_filter' => true,
    'is_search' => $doSearch,
    'uparams' => $Params['user_parameters_unordered'],
];
$filterParams = erLhcoreClassSearchHandler::getParams($doSearch
    ? array_merge($searchParams, ['use_override' => true])
    : $searchParams);

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);

$pages = new lhPaginator;
$pages->items_total = erLhcoreClassModelSms77Phone::getCount($filterParams['filter']);
$pages->translationContext = 'chat/activechats';
$pages->serverURL = erLhcoreClassDesign::baseurl('sms77/list') . $append;
$pages->paginate();

$tpl = erLhcoreClassTemplate::getInstance('lhsms77/list.tpl.php');
$tpl->set('pages', $pages);

if ($pages->items_total > 0)
    $tpl->set('items', erLhcoreClassModelSms77Phone::getList(array_merge([
        'limit' => $pages->items_per_page,
        'offset' => $pages->low,
    ], $filterParams['filter'])));

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('sms77/list');
$tpl->set('input', $filterParams['input_form']);
$tpl->set('inputAppend', $append);

$Result['content'] = $tpl->fetch();
$Result['path'] = [
    [
        'title' => erTranslationClassLhTranslation::getInstance()
            ->getTranslation('module/fbmessenger', 'Sms77 Phones'),
        'url' => erLhcoreClassDesign::baseurl('sms77/list'),
    ],
];
