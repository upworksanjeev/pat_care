<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;
use App\Models\Rating;
use App\Models\VariationAttribute;
use App\Http\Requests\API\ProductRequest;

class FrontController extends Controller
{
  public function blog($slug)
    {
		$metaInfo= [
					'title'=>'PetParent blog page',
					'description'=>'Meta descrption blog page',
					'slug'=>$slug
				];

		return view('frontend.blog', compact('metaInfo'));

    }
	public function brand($slug,$brandid)
    {
		$metaInfo= [
					'title'=>'PetParent brand page',
					'description'=>'Meta descrption brand page',
					'slug'=>$slug,
					'brandid'=>$brandid
				];

		return view('frontend.brand', compact('metaInfo'));

    }
	public function cart()
    {
		$metaInfo= [
					'title'=>'PetParent cart page',
					'description'=>'Meta descrption cart page'
				];

		return view('frontend.cart', compact('metaInfo'));

    }
	public function cartnew()
    {
		$metaInfo= [
					'title'=>'PetParent cart page',
					'description'=>'Meta descrption cart page'
				];

		return view('frontend.cartnew', compact('metaInfo'));

    }
	public function category($slug, $id)
    {
		$metaInfo= [
					'title'=>'PetParent category page',
					'description'=>'Meta descrption category page',
					'catslug'=>$slug,
          'id'=>$id
				];

		return view('frontend.category', compact('metaInfo'));

    }
	public function checkout()
    {
		$metaInfo= [
					'title'=>'PetParent checkout page',
					'description'=>'Meta descrption checkout page'
				];

		return view('frontend.checkout', compact('metaInfo'));

    }
	public function chowhub($cartid,$cartkey)
    {
		$metaInfo= [
					'title'=>'PetParent chowhub page',
					'description'=>'Meta descrption chowhub page',
          'cartid'=>$cartid,
          'cartkey'=>$cartkey
				];

		return view('frontend.chowhub', compact('metaInfo'));

    }
	public function dashboard()
    {
		$metaInfo= [
					'title'=>'PetParent dashboard page',
					'description'=>'Meta descrption dashboard page'
				];

		return view('frontend.dashboard', compact('metaInfo'));

    }
  public function store()
    {
		$metaInfo= [
					'title'=>'PetParent store page',
					'description'=>'Meta descrption store page'
				];

		return view('frontend.store', compact('metaInfo'));

    }
	public function litterhub($cartid,$cartkey)
    {
		$metaInfo= [
					'title'=>'PetParent litterhub page',
					'description'=>'Meta descrption litterhub page',
          'cartid'=>$cartid,
          'cartkey'=>$cartkey
				];

		return view('frontend.litterhub', compact('metaInfo'));

    }
	public function payment()
    {
		$metaInfo= [
					'title'=>'PetParent payment page',
					'description'=>'Meta descrption payment page'
				];

		return view('frontend.payment', compact('metaInfo'));

    }
	public function profile()
    {
		$metaInfo= [
					'title'=>'PetParent profile page',
					'description'=>'Meta descrption profile page'
				];

		return view('frontend.profile', compact('metaInfo'));

    }
	public function register()
    {
		$metaInfo= [
					'title'=>'PetParent register page',
					'description'=>'Meta descrption register page'
				];

		return view('frontend.register', compact('metaInfo'));

    }
	public function login()
    {
		$metaInfo= [
					'title'=>'PetParent login page',
					'description'=>'Meta descrption login page'
				];

		return view('frontend.login', compact('metaInfo'));

    }
	public function logout()
    {
		$metaInfo= [
					'title'=>'PetParent logout page',
					'description'=>'Meta descrption logout page'
				];

		return view('frontend.logout', compact('metaInfo'));

    }
	public function pagination()
    {
		$metaInfo= [
					'title'=>'PetParent logout page',
					'description'=>'Meta descrption logout page'
				];

		return view('frontend.pagination', compact('metaInfo'));

    }
	public function productDeatials($slug,$id)
    {
  		if($slug){
        $products = Product::with(['brand', 'productGallery'])->find($id);
        $ratings =   Rating::where('product_id',$id)->get();

        $arrayGallery=[];
        $arraybrand=[];
        $review=[];
        $aggregateRating=[];
        $overall=[];

        if($products){
          foreach ($products->productGallery as $key => $productGallery) {
            array_push($arrayGallery,$productGallery->image_path);
          }
          $arraybrand = array (
            '@type' => 'Brand',
            'name' => $products->brand->name,
          );

          if($ratings){
            foreach ($ratings as $key => $value) {
                $overall[]=$value->rating;
            }
            foreach ($ratings as $key => $value) {
                $overall[]=$value->rating;
            }
            if(count($overall)>0){
              $totalSum=array_sum($overall);
              $totalCount=count($overall);
              $overAllRating=$totalSum/$totalCount;
              $maxRating= max($overall);
              $review= array (
                    '@type' => 'Review',
                    'reviewRating' =>
                      array (
                        '@type' => 'Rating',
                        'ratingValue' => $overAllRating,
                        'bestRating' => $maxRating,
                      ),
                    'author' =>
                      array (
                        '@type' => 'Person',
                        'name' => 'Fred Benson',
                      ),
                  );
              $aggregateRating  = array (
                  '@type' => 'AggregateRating',
                  'ratingValue' => $overAllRating,
                  'reviewCount' => $totalCount,
              );
            }
          }

    			$productSchema = array (
    							  '@context' => 'https://schema.org/',
    							  '@type' => 'Product',
    							  'name' => $products->productName,
    							  'image' =>$arrayGallery,
    							  'description' => $products->description,
    							  'sku' => $products->sku,
    							  'brand' =>$arraybrand,
    							  'review' =>$review,
    							  'aggregateRating' =>$aggregateRating,
    							  'offers' => array (
      								'@type' => 'Offer',
      								'url' => 'https://example.com/anvil',
      								'priceCurrency' => 'USD',
      								'price' => '119.99',
      								'priceValidUntil' => '2020-11-22',
      								'itemCondition' => 'https://schema.org/UsedCondition',
      								'availability' => 'https://schema.org/InStock',
    		            )
    				);

            $metaInfo= [
      					'title'=>$products->productName,
      					'description'=>$products->description,
						'schemaResponse'=>$productSchema,
						'slug'=>$slug,
						'id'=>$id
      				];
    			return view('frontend.productDeatials', compact('metaInfo'));
        }else{
          return redirect('/');
        }
  		}else{
        return redirect('/');
      }
    }
}
