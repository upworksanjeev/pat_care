<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LightSpeed;
class HomeController extends Controller
{
  public function index()
    {
		$metaInfo= [
					'title'=>'PetParent home page',
					'description'=>'Meta descrption'
				];

		return view('frontend.home', compact('metaInfo'));

    }
		public function lightspeedapptoken(Request $request)
    {
			$inputs=$request->all();

			$lightspeed=LightSpeed::orderBy('id', 'asc')->first();
		
			if($lightspeed){
				$lightspeed->update(['code' => $inputs['code']]);
			}else{
				LightSpeed::create(['code' => $inputs['code']]);
			}
		
			return redirect()->route('dashboard');

    }
}
