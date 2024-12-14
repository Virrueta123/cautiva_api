<?php

namespace App\Http\Controllers;

use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use Illuminate\Http\Request;

class sunat_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function generateKey()
    {
        // $pfx = file_get_contents('mycert.pfx');
        // $password = 'YOUR-PASSWORD';

        // $certificate = new X509Certificate($pfx, $password);

        // $see->setCertificate($certificate->export(X509ContentType::PEM));
    }

    
}
