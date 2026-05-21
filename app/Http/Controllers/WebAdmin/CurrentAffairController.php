<?php
namespace App\Http\Controllers;

use App\Models\CurrentAffair;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CurrentAffairController extends Controller
{
    public function index()
    {
        return view('currentAffairs.index');
    }
   
}


?>