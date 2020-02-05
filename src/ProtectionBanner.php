<?php

namespace Markohs\ProtectionBanner;

use Illuminate\Http\Request;

class ProtectionBanner
{
    public static function generate_accept_link(Request $r)
    {

	    $return_link = $r->url();
	    $return_link = $return_link . '?confirmed=yes';

	    if ($r->input('id')){
	        $return_link = $return_link . '&id='.$r->input('id');
	    }

	    if ($r->input('s')){
	        $return_link = $return_link . '&s='.$r->input('s');
	    }

	    if ($r->input('utm_source')){
	        $return_link = $return_link . '&utm_source='.$r->input('utm_source');
	    }

	    if ($r->input('utm_medium')){
	        $return_link = $return_link . '&utm_medium='.$r->input('utm_medium');
	    }

	    if ($r->input('utm_campaign')){
	        $return_link = $return_link . '&utm_campaign='.$r->input('utm_campaign');
	    }

	    return $return_link;

	}
}