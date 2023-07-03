<?php

use function PHPUnit\Framework\assertEquals;

$TERM1 = 'file not found';
$TERM1_EN = 'File not found';
$TERM1_NL = 'Bestand niet gevonden';
$TERM1_EN_DB = 'File not found from db';
$TERM1_NL_DB = 'File not found from db';
$TERM2 = 'file not found. it might be in trash.';
$TERM2_EN = 'File not found. It might be in trash.';
$TERM2_NL = 'Bestand niet gevonden. Het bestand is waarschijnlijk verwijderd.';
$TERM2_EN_DB = 'File not found from db. It might be in trash.';
$TERM2_NL_DB = 'Bestand niet gevonden uit de database. Het bestand is waarschijnlijk verwijderd.';

it('can get translations for language files', function () use ($TERM1, $TERM2, $TERM1_EN, $TERM2_EN) {
    assertEquals($TERM1_EN, __($TERM1));
    assertEquals($TERM2_EN, __($TERM2));
});

it('can get translations for language files for the current locale', function () use ($TERM1, $TERM2, $TERM1_NL, $TERM2_NL) {
    app()->setLocale('nl');

    assertEquals($TERM1_NL, __($TERM1));
    assertEquals($TERM2_NL, __($TERM2));
});

it('it will prefer a DB translation over a file translation by default', function () use ($TERM1, $TERM1_EN_DB, $TERM2, $TERM2_EN_DB) {
    createTrans('*', $TERM1, ['en' => $TERM1_EN_DB]);
    createTrans('*', $TERM2, ['en' => $TERM2_EN_DB]);

    assertEquals($TERM1_EN_DB, __($TERM1));
    assertEquals($TERM2_EN_DB, __($TERM2));
});

it('will default to fallback if the locale is missing', function () use ($TERM1, $TERM1_EN_DB) {
    app()->setLocale('de');
    createTrans('*', $TERM1, ['en' => $TERM1_EN_DB]);

    assertEquals($TERM1_EN_DB, __($TERM1));
});
