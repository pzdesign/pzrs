<?php 
namespace App\Model;

class MyValidators
{
    const USERNAME = 'UserFormRules::validateUsername';
    const EMAIL_DOMAIN = 'UserFormRules::validateEmailDomain';

    public static function validateUsername(IControl $control)
    {
        // validace uživatelského jména
    }

    public static function validateEmailDomain(IControl $control, $domain)
    {
        // validace, zda se jedné o e-mail z domény $domain
    }
}
