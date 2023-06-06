<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Application;
use App\Models\ApplicationCourse;
use App\Models\ApplicationPayment;
use App\Models\ApplicationDocument;
use App\Models\LevelInformation;
use App\Models\User;
use App\Models\Faq;

use Redirect;
use Auth;
use File;
use DB;
use Mail;
use App\Mail\SendAcknowledgment;


class LevelController extends Controller
{
    //admin part
    public function index(Request $request)
    {

        $level =  LevelInformation::get();
        $collection=ApplicationPayment:: where('status','0')->get();
        $collection1=ApplicationPayment:: where('status','1')->get();
        $collection2=ApplicationPayment:: where('status','2')->get();
        return view("level.level",['level'=>$level,'collection'=>$collection,'collection1'=>$collection1,'collection2'=>$collection2]);
    }

    public function admin_view()
     {
     $ApplicationCourse=ApplicationCourse::get();

       $id= $ApplicationCourse[0]->user_id;

    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $ApplicationPayment=ApplicationPayment::get();
    return view('level.admin_course_view',['data'=>$data,'ApplicationCourse'=>$ApplicationCourse,'ApplicationPayment'=>$ApplicationPayment]);
    }

    public function level_view($id)
    {
     $data = LevelInformation::find(dDecrypt($id));
     return view('level.levelview_page',['data'=>$data]);
    }

    public function update_level($id)
    {
    $data = LevelInformation::find(dDecrypt($id));
    return view('level.update_level',['data'=>$data]);
    }


    public function update_level_post(Request $request,$id)
    {

        $request->validate(
            [
            'level_Information' =>'required',
            'Prerequisites' =>'required',
            'documents_required' =>'required',
            'validity' =>'required',
            'fee_structure' =>'required',
            'timelines' =>'required',
            ]
        );
         //  dd($request->all());
            $data=LevelInformation::find(dDecrypt($id));
            $data->level_Information = $request->level_Information;
            $data->Prerequisites =$request->Prerequisites;
            $data->documents_required =$request->documents_required;
            $data->validity =$request->validity;
            $data->fee_structure =$request->fee_structure;
            $data->timelines =$request->timelines;
            $data->save();
            return redirect('/level')->with('success', 'level Update successfull!! ');

    }

  //form part in Tp

  public function level1tp(Request $request)
  {

    $faqs=Faq::where('category',1)->orderby('sort_order','Asc')->get();
     $Application =Application::whereuser_id(Auth::user()->id)->get();
    // dd($Application);
    $file =ApplicationDocument::get();
    $item=LevelInformation:: whereid('1')->get();
    $collection=ApplicationPayment::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
    $collections=Application::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
    $course=ApplicationCourse::wherestatus('0')->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
   if(Auth::user()->country == $this->get_india_id()){

     if(count($course) == '0')
     {
         $currency = '₹';
         $total_amount = '0';

     }elseif(count($course) <= 5 )
     {
         $currency = '₹';
         $total_amount = '1000';


     }elseif(count($course) <= 10 )
     {
         $currency = '₹';
         $total_amount =  '2000';

     }else{
         $currency = '₹';
         $total_amount =   '3000';

     }
   }

   elseif(in_array(Auth::user()->country,$this->get_saarc_ids()))

   {


    if(count($course) == '0')
    {
         $currency = 'US $';
         $total_amount = '0';

    }elseif(count($course) <= 5 )
    {
        $currency = 'US $';
        $total_amount =  '15';


    }elseif(count($course) <= 10 )

    {
        $currency = 'US $';
        $total_amount = '30';
    }else{
        $currency = 'US $';
        $total_amount =  '45';
    }


   }else{

    if(count($course) == '0')
    {
        $currency = 'US $';
        $total_amount = '';

    }elseif(count($course) <= 5 )
    {
        $currency = 'US $';
        $total_amount = '50';


    }elseif(count($course) <= 10 )
    {
        $currency = 'US $';
        $total_amount = '100';

    }else{
        $currency = 'US $';
        $total_amount =  '150';

    }
}

    $id=Auth::user()->id;
   // dd($id);
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $Country =Country::get();
    return view('level.leveltp',['Application'=>$Application,'item'=>$item,'Country'=>$Country,'data'=>$data,'course'=>$course,'currency'=>$currency,'total_amount'=>$total_amount,'collection'=>$collection,'file'=>$file,'faqs'=>$faqs]);
  }


//Code by gaurav
public function newapplication()
{
  $item=LevelInformation:: whereid('1')->get();
  $collection=ApplicationPayment::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
  $collections=Application::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
  $course=ApplicationCourse::wherestatus('0')->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
 if(Auth::user()->country == $this->get_india_id()){

   if(count($course) == '0')
   {
       $currency = '₹';
       $total_amount = '0';

   }elseif(count($course) <= 5 )
   {
       $currency = '₹';
       $total_amount = '1000';


   }elseif(count($course) <= 10 )
   {
       $currency = '₹';
       $total_amount =  '2000';

   }else{
       $currency = '₹';
       $total_amount =   '3000';

   }
 }

 elseif(in_array(Auth::user()->country,$this->get_saarc_ids()))

 {


  if(count($course) == '0')
  {
       $currency = 'US $';
       $total_amount = '0';

  }elseif(count($course) <= 5 )
  {
      $currency = 'US $';
      $total_amount =  '15';


  }elseif(count($course) <= 10 )

  {
      $currency = 'US $';
      $total_amount = '30';
  }else{
      $currency = 'US $';
      $total_amount =  '45';
  }


 }else{

  if(count($course) == '0')
  {
      $currency = 'US $';
      $total_amount = '';

  }elseif(count($course) <= 5 )
  {
      $currency = 'US $';
      $total_amount = '50';


  }elseif(count($course) <= 10 )
  {
      $currency = 'US $';
      $total_amount = '100';

  }else{
      $currency = 'US $';
      $total_amount =  '150';

  }
}

  $id=Auth::user()->id;
 // dd($id);
  $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
  $Country =Country::get();
  return view('level.newapplication',['item'=>$item,'Country'=>$Country,'data'=>$data,'course'=>$course,'currency'=>$currency,'total_amount'=>$total_amount,'collection'=>$collection]);
}




  public function level2tp()
  {
    $faqs=Faq::where('category',2)->orderby('sort_order','Asc')->get();

    $item=LevelInformation::whereid('2')->get();
    $collection=ApplicationPayment::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
    $course=ApplicationCourse::wherestatus('0')->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
    if(Auth::user()->country == $this->get_india_id()){

        if(count($course) == '0')
        {
            $currency = '₹';
            $total_amount = '0';

        }elseif(count($course) <= 5 )
        {
            $currency = '₹';
            $total_amount = '2500';


        }elseif(count($course) <= 10 )
        {
            $currency = '₹';
            $total_amount =  '5000';

        }else{
            $currency = '₹';
            $total_amount =   '10000';

        }
      }

      elseif(in_array(Auth::user()->country,$this->get_saarc_ids()))

      {


       if(count($course) == '0')
       {
            $currency = 'US $';
            $total_amount = '0';

       }elseif(count($course) <= 5 )
       {
           $currency = 'US $';
           $total_amount =  '35';


       }elseif(count($course) <= 10 )

       {
           $currency = 'US $';
           $total_amount = '75';
       }else{
           $currency = 'US $';
           $total_amount =  '150';
       }


      }else{

       if(count($course) == '0')
       {
           $currency = 'US $';
           $total_amount = '0';

       }elseif(count($course) <= 5 )
       {
           $currency = 'US $';
           $total_amount = '100';


       }elseif(count($course) <= 10 )
       {
           $currency = 'US $';
           $total_amount = '200';

       }else{
           $currency = 'US $';
           $total_amount =  '400';

       }
   }
    $id=Auth::user()->id;
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $Country =Country::get();
    return view('level.leveltp',['item'=>$item,'Country'=>$Country,'data'=>$data,'course'=>$course,'currency'=>$currency,'collection'=>$collection,'total_amount'=>$total_amount,'faqs'=>$faqs]);
  }


  public function level3tp()
  {
    $faqs=Faq::where('category',3)->orderby('sort_order','Asc')->get();

    $item=LevelInformation:: whereid('3')->get();
    $course=ApplicationCourse::wherestatus('0')->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
  //  dd($course);
    $collection=ApplicationPayment::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();

    if(Auth::user()->country == $this->get_india_id()){


        if(count($course) == '0')
        {
            $currency = '₹';
            $total_amount = '0';

        }elseif(count($course) <= 5 )
        {
            $currency = '₹';
            $total_amount = '1000';


        }elseif(count($course) <= 10 )
        {
            $currency = '₹';
            $total_amount =  '2000';

        }else{
            $currency = '₹';
            $total_amount =   '3000';

        }
      }

      elseif(in_array(Auth::user()->country,$this->get_saarc_ids()))

      {


       if(count($course) == '0')
       {
            $currency = 'US $';
            $total_amount = '0';

       }elseif(count($course) <= 5 )
       {
           $currency = 'US $';
           $total_amount =  '15';


       }elseif(count($course) <= 10 )

       {
           $currency = 'US $';
           $total_amount = '30';
       }else{
           $currency = 'US $';
           $total_amount =  '45';
       }


      }else{

       if(count($course) == '0')
       {
           $currency = 'US $';
           $total_amount = '';

       }elseif(count($course) <= 5 )
       {
           $currency = 'US $';
           $total_amount = '50';


       }elseif(count($course) <= 10 )
       {
           $currency = 'US $';
           $total_amount = '100';

       }else{
           $currency = 'US $';
           $total_amount =  '150';

       }
   }
    $id=Auth::user()->id;

    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $Country =Country::get();
    return view('level.leveltp',['item'=>$item,'Country'=>$Country,'data'=>$data,'course'=>$course,'currency'=>$currency,'collection'=>$collection,'total_amount'=>$total_amount,'faqs'=>$faqs]);
  }
  public function level4tp()
  {
    $faqs=Faq::where('category',4)->orderby('sort_order','Asc')->get();

    $item=LevelInformation:: whereid('4')->get();
    $course=ApplicationCourse::whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
    $collection=ApplicationPayment::wherestatus('1')->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
    if(Auth::user()->country == $this->get_india_id()){


        if(count($course) == '0')
        {
            $currency = '₹';
            $total_amount = '0';

        }elseif(count($course) <= 5 )
        {
            $currency = '₹';
            $total_amount = '1000';


        }elseif(count($course) <= 10 )
        {
            $currency = '₹';
            $total_amount =  '2000';

        }else{
            $currency = '₹';
            $total_amount =   '3000';

        }
      }

      elseif(in_array(Auth::user()->country,$this->get_saarc_ids()))

      {


       if(count($course) == '0')
       {
            $currency = 'US $';
            $total_amount = '0';

       }elseif(count($course) <= 5 )
       {
           $currency = 'US $';
           $total_amount =  '15';


       }elseif(count($course) <= 10 )

       {
           $currency = 'US $';
           $total_amount = '30';
       }else{
           $currency = 'US $';
           $total_amount =  '45';
       }


      }else{

       if(count($course) == '0')
       {
           $currency = 'US $';
           $total_amount = '';

       }elseif(count($course) <= 5 )
       {
           $currency = 'US $';
           $total_amount = '50';


       }elseif(count($course) <= 10 )
       {
           $currency = 'US $';
           $total_amount = '100';

       }else{
           $currency = 'US $';
           $total_amount =  '150';

       }
   }
    $id=Auth::user()->id;
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $Country =Country::get();
    return view('level.leveltp',['item'=>$item,'Country'=>$Country,'data'=>$data,'course'=>$course,'currency'=>$currency,'collection'=>$collection,'total_amount'=>$total_amount,'faqs'=>$faqs]);
  }

  public function delete_course($id)
  {
    $res=ApplicationCourse::find(dDecrypt($id))->delete();
    return back()->with('success', 'Course Delete successfull!!');
  }

  public function new_application(Request $request){

   // dd($request);
        $aplication = new Application();
        $aplication->level_id=$request->level_id;
        $aplication->user_id=Auth::user()->id;
        $aplication->state=Auth::user()->state;
        $aplication->state=Auth::user()->state;
        $aplication->country=Auth::user()->country;
        $aplication->Person_Name=$request->Person_Name;
        $aplication->Contact_Number=$request->Contact_Number;
        $aplication->Email_ID=$request->Email_ID;
        $aplication->city =Auth::user()->city;
        $aplication->ip= getHostByName(getHostName());
        $aplication->save();
        return back();
    }


 //course payment  controller

        public function new_application_course(Request $request)
        {

           // dd("$request->months");
         
            // dd($request->all());


       
        
        //dd("test");
        $course_name=$request->course_name;
        $course_duration=$request->course_duration;
        $eligibility=$request->eligibility;
        $mode_of_course=$request->mode_of_course;
        $course_brief=$request->course_brief;
        $level_id =$request->level_id;

        $years=$request->years;
        $months=$request->months;
        $days=$request->days;
        $hours=$request->hours;

        $user_id=Auth::user()->id;



          //document upload
          if($request->hasfile('doc1'))
          {
              $doc1 = $request->file('doc1');
          }

          if($request->hasfile('doc2'))
          {
            $doc2 = $request->file('doc2');
          }
          if($request->hasfile('doc3'))
          {
            $doc3 = $request->file('doc3');
          }

        
        $aplication =Application::whereuser_id(Auth::user()->id)->wherelevel_id($request->level_id)->get();
       // dd($aplication[0]->id);
        if(count($aplication)==0){

            $aplication =Application::find($aplication[0]->id);
            $aplication->level_id=$request->level_id;
            $aplication->user_id=Auth::user()->id;
            $aplication->state=Auth::user()->state;
            $aplication->state=Auth::user()->state;
            $aplication->country=Auth::user()->country;
            $aplication->Person_Name=$request->Person_Name;
            $aplication->Contact_Number=$request->Contact_Number;
            $aplication->Email_ID=$request->Email_ID;
            $aplication->Email_ID =Auth::user()->city;
            $aplication->ip= getHostByName(getHostName());
            $aplication->save();

       }
        $aplication=$aplication->first();



    
        for($i=0; $i<count($course_name); $i++){
            if(empty($course_name[$i])){
                continue;
            }
            $file = new ApplicationCourse();
            $file->application_id =$aplication->id;
            $file->course_name=$course_name[$i];

            $file->years=$years[$i];
            $file->months=$months[$i];
            $file->days=$days[$i];
            $file->hours=$hours[$i];

            $file->level_id=$request->level_id;
            $file->user_id=Auth::user()->id;
            $file->country=Auth::user()->country;
         /* $file->course_duration=$course_duration[$i];*/
            $file->eligibility=$eligibility[$i];
            $file->mode_of_course=$mode_of_course[$i];
            $file->course_brief=$course_brief[$i];
            $file->valid_from=$request->created_at;
            $file->status='0';
            $file->payment='false';

            
            $file->save();


            $data = new ApplicationDocument;
            $data->document_type_name='doc1';
            $name =$doc1[$i]->getClientOriginalName();
            $filename = time().$name;
            $doc1[$i]->move('documnet/',$filename);
            $data->document_file =  $filename;
            $data->user_id = Auth::user()->id;
            $data->application_id=$aplication->id;
            $data->level_id=$request->level_id;
            $data->course_number=$file->id;
            $data->save();

            $data = new ApplicationDocument;
             $data->document_type_name='doc2';
            $doc2 = $request->file('doc2');
            $name =$doc2[$i]->getClientOriginalName();
            $filename = time().$name;
            $doc2[$i]->move('documnet/',$filename);
            $data->document_file =  $filename;
            $data->user_id = Auth::user()->id;
            $data->application_id=$aplication->id;
            $data->level_id=$request->level_id;
            $data->course_number=$file->id;
            $data->save();



             $data = new ApplicationDocument;
             $data->document_type_name='doc3';
            $img = $request->file('doc3');
            $name =$doc3[$i]->getClientOriginalName();
            $filename = time().$name;
            $doc3[$i]->move('documnet/',$filename);
            $data->document_file =  $filename;
            $data->user_id = Auth::user()->id;
            $data->application_id=$aplication->id;
            $data->level_id=$request->level_id;
            $data->course_number=$file->id;
            $data->save();

        }
       
        if($request->level_id =='1')
        {

        return  redirect('/level-first')->with('success','Course  successfully  Added!!!!');

        }elseif($request->level_id =='2')
        {

            return  redirect('/level-second')->with('success','Course successfully Added!!!!');

        }elseif($request->level_id =='3')
        {
            return  redirect('/level-third')->with('success','Course successfully Added!!!!');
        }else
        {
            return  redirect('/level-fourth')->with('success','Course successfully Added!!!!');
        }

    }


    //couser payment


            public function new_application_payment(Request $request)
            {
                 //dd($request->course_id);

                 $aplication =Application::whereuser_id(Auth::user()->id)->wherelevel_id($request->level_id)->get();
                 // dd($aplication[0]->id);
                  if(count($aplication)==0){

                      $aplication =Application::find($aplication[0]->id);
                      $aplication->level_id=$request->level_id;
                      $aplication->user_id=Auth::user()->id;
                      $aplication->state=Auth::user()->state;
                      $aplication->state=Auth::user()->state;
                      $aplication->country=Auth::user()->country;
                      $aplication->Person_Name=$request->Person_Name;
                      $aplication->Contact_Number=$request->Contact_Number;
                      $aplication->Email_ID=$request->Email_ID;
                      $aplication->Email_ID =Auth::user()->city;
                      $aplication->ip= getHostByName(getHostName());
                      $aplication->save();

                 }
                  $aplication=$aplication->first();

            $item = new ApplicationPayment;
            $item->level_id=$request->level_id;
            $item->user_id=Auth::user()->id;
            $item->amount = $request->amount;
            $item->payment_date =$request->payment_date;
            $item->payment_details =$request->payment_transaction_no;
            $item->course_count=$request->course_count;
            $item->currency=$request->currency;
            $item->country=$request->coutry;
            $item->status='0';
            $item->application_id=$aplication->id;
            if($request->hasfile('payment_details_file'))
            {
             $img = $request->file('payment_details_file');
             $name =$img->getClientOriginalName();
             $filename = time().$name;
             $img->move('uploads/',$filename);
             $item->payment_details_file=$filename;
            }
            $item->save();

           // dd($item->id);

            if($request->level_id =='1')
            {

                foreach($request->course_id as $items)
                {
                $ApplicationCourse=ApplicationCourse::find($items);
                $ApplicationCourse->status='1';
                $ApplicationCourse->payment=$item->id;
                $ApplicationCourse->update();
                }
                $ApplicationCourse->save();

              return  redirect('/level-first')->with('success','Payment Done successfully!!!!');

         //count payment in course status true

            }elseif($request->level_id =='2')
            {
                //dd("hii");
                foreach($request->course_id as $item)
                {
                $ApplicationCourse=ApplicationCourse::find($item);
                $ApplicationCourse->payment='True';
                $ApplicationCourse->status='1';
                $ApplicationCourse->update();
                }
                $ApplicationCourse->save();

                return  redirect('/level-second')->with('success','Payment Done successfully!!!!');;

            }elseif($request->level_id =='3')
            {
                foreach($request->course_id as $item)
                {
                $ApplicationCourse=ApplicationCourse::find($item);
                $ApplicationCourse->payment='True';
                $ApplicationCourse->status='1';
                $ApplicationCourse->update();
                }
                $ApplicationCourse->save();

                return  redirect('/level-third')->with('success',' Payment Done successfully!!!!');;
            }else
            {
                return  redirect('/level-fourth')->with('success','Payment Done successfully!!!!');;
            }


      }


  //level information view page 4 url

public function previews_application1($ids)
{
    $id=Auth::user()->id;
    $item=LevelInformation:: whereid('1')->get();
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $ApplicationCourse=ApplicationCourse::where('user_id',$id)->wherepayment($ids)->wherelevel_id($item[0]->id)->get();
    $ApplicationPayment=ApplicationPayment::where('user_id',$id)->whereid($ids)->wherelevel_id($item[0]->id)->get();
    return view('level.level-previous_view',['data'=>$data,'ApplicationCourse'=>$ApplicationCourse,'ApplicationPayment'=>$ApplicationPayment]);
}

public function previews_application2($ids)
{
    $id=Auth::user()->id;
    $item=LevelInformation:: whereid('2')->get();
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $ApplicationCourse=ApplicationCourse::where('user_id',$id)->wherepayment($ids)->wherelevel_id($item[0]->id)->get();
    $ApplicationPayment=ApplicationPayment::where('user_id',$id)->whereid($ids)->wherelevel_id($item[0]->id)->get();
    return view('level.level-previous_view',['data'=>$data,'ApplicationCourse'=>$ApplicationCourse,'ApplicationPayment'=>$ApplicationPayment]);
}


public function previews_application3($ids)
{
    $id=Auth::user()->id;
    $item=LevelInformation:: whereid('3')->get();
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $ApplicationCourse=ApplicationCourse::where('user_id',$id)->wherepayment($ids)->wherelevel_id($item[0]->id)->get();
    $ApplicationPayment=ApplicationPayment::where('user_id',$id)->whereid($ids)->wherelevel_id($item[0]->id)->get();
    return view('level.level-previous_view',['data'=>$data,'ApplicationCourse'=>$ApplicationCourse,'ApplicationPayment'=>$ApplicationPayment]);
}


public function previews_application4()
{
    $id=Auth::user()->id;
    $item=LevelInformation:: whereid('4')->get();
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $ApplicationCourse=ApplicationCourse::where('user_id',$id)->wherelevel_id($item[0]->id)->get();
    $ApplicationPayment=ApplicationPayment::where('user_id',$id)->wherelevel_id($item[0]->id)->get();
    return view('level.level-previous_view',['data'=>$data,'ApplicationCourse'=>$ApplicationCourse,'ApplicationPayment'=>$ApplicationPayment]);
}


//level upgrade section
public function application_upgrade2()
{
    $id=Auth::user()->id;
    $item=LevelInformation:: whereid('1')->get();
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $Course=ApplicationCourse::where('user_id',$id)->wherelevel_id($item[0]->id)->get();
    return view('level.level-upgrade',['Course'=>$Course,'data'=>$data])->with('success', 'upgrade level Courses First ');
}


public function application_upgrade3()
{
    $id=Auth::user()->id;
    $item=LevelInformation:: whereid('2')->get();
  //  dd($item);
    $data=DB::table('users')->where('users.id',$id)->select('users.*','cities.name as city_name','states.name as state_name','countries.name as country_name')->join('countries','users.country', '=', 'countries.id')->join('cities','users.city', '=', 'cities.id')->join('states','users.state', '=', 'states.id')->first();
    $Course=ApplicationCourse::where('user_id',$id)->wherelevel_id($item[0]->id)->get();
    return view('level.level-upgrade',['Course'=>$Course,'data'=>$data])->with('success', 'Upgrade level Course Second ');
}


public function application_upgrade4()
{
    return view('level.level-upgrade');
}



  function get_india_id(){
    $india=Country::where('name','India')->get('id')->first();
   return $india->id;
   }

function get_saarc_ids(){
    //Afghanistan, Bangladesh, Bhutan, India, Maldives, Nepal, Pakistan and Sri-Lanka
    $saarc=Country::whereIn('name',Array('Afghanistan', 'Bangladesh', 'Bhutan', 'Maldives', 'Nepal', 'Pakistan', 'Sri Lanka'))->get('id');
    $saarc_ids=Array();
    foreach($saarc as $val)$saarc_ids[]=$val->id;
    return $saarc_ids;
}

public function upload_document($id)
{

    $data =ApplicationPayment::whereid(dDecrypt($id))->get();
    $file =ApplicationDocument::whereapplication_id($data[0]->application_id)->get();
    return view('level.upload_document',['file'=>$file,'data'=>$data]);

}



public function  course_status()
{

}



public function  payment_status()
{

}


public function appliction_status()
{

}

public function preveious_app_status($id)
{

    $user=ApplicationPayment::find(dDecrypt($id));
    $user_info=User::find($user->user_id);

    if($user->status == '0')
    {
       // dd('hello');
         $user->status='1';

    }elseif($user->status == '1')
    {
        //dd("hii");
        $user->status= '2';
        $file=ApplicationPayment::whereid(dDecrypt($id))->get('application_id');
        $files=Application::whereid($file[0]->application_id)->get('id');

        $file=Application::find($files[0]->id);
        $file->status= '1';
        $file->update();

    }
    $user->update();

    //Mail sending scripts starts here
    $send_acknowledgment_letter = [
        'subject' => 'Acknowledgment letter for application',
        'body' => 'Payment has been approved for your application, please check attachment as pdf for acknowledgment letter.',
        'attachment' => asset('acknowledgment-letter.pdf')
        ];

        Mail::to($user_info->email)->send(new SendAcknowledgment($send_acknowledgment_letter));
       //Mail sending script ends here


    // $file=ApplicationPayment::whereid($id)->get('application_id');
    // $file=ApplicationCourse::where('application_id',$file[0]->application_id)->get('id');


    // foreach($file as $item)
    // {
    //     $ApplicationCourse=ApplicationCourse::find($item->id);
    //     $ApplicationCourse->status=($ApplicationCourse->status==1?'0':'1');
    //     $ApplicationCourse->update();
    // }

    //status change in application


    return Redirect::back()->with('success', 'Status Changed Successfully');
}


public function  uploads_document(Request $request)
{

  // dd($request->all());
     // if($request->hasfile('file'))
    // {
    //     $img = $request->file('file');
    //     $name =$img->getClientOriginalName();
    //     $filename = time().$name;
    //     $img->move('profile/',$filename);
    //     $data->file=$filename;
    // }

    $aplication =Application::whereuser_id(Auth::user()->id)->wherelevel_id($request->level_id)->get();
    if(count($aplication)==0){
        $aplication =Application::create(['country'=>$request->coutry,'state'=>$request->state,'user_id'=>Auth::user()->id,'level_id'=>$request->level_id]);
       // dd($aplication);
    }
    $aplication=$aplication->first();


    if($request->hasfile('doc1'))
    {
        $data = new ApplicationDocument;
        $img = $request->file('doc1');
        $name =$img->getClientOriginalName();
        $filename = time().$name;
        $img->move('documnet/',$filename);

        $data->status = 1;
        $data->document_type_name = "Doc 1";
        $data->document_file =  $filename;
        $data->user_id = Auth::user()->id;
        $data->application_id=$aplication->id;
        $data->level_id=$request->level_id;
        $data->save();
    }
    if($request->hasfile('doc2'))
    {

        $data = new ApplicationDocument;

        $img = $request->file('doc2');
        $name =$img->getClientOriginalName();
        $filename = time().$name;
        $img->move('documnet/',$filename);
        $data->status = 1;
        $data->document_type_name = "Doc 2";
        $data->document_file =  $filename;
        $data->user_id = Auth::user()->id;
        $data->application_id=$aplication->id;
        $data->level_id=$request->level_id;
        $data->save();
    }
    if($request->hasfile('doc3'))
    {

        $data = new ApplicationDocument;

        $img = $request->file('doc3');
        $name =$img->getClientOriginalName();
        $filename = time().$name;
        $img->move('documnet/',$filename);
        $data->status = 1;
        $data->document_type_name = "Doc 3";
        $data->document_file =  $filename;
        $data->user_id = Auth::user()->id;
        $data->application_id=$aplication->id;
        $data->level_id=$request->level_id;
        $data->save();
    }
    return back()->with('success','Done successfully!!!!');
}

//course model data get
public function course_list(Request $request)
{
  $item=LevelInformation:: whereid('1')->get();
  $ApplicationCourse=ApplicationCourse::whereid($request->id)->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
  $Document=ApplicationDocument::wherecourse_number($ApplicationCourse[0]->id)->get();
  return response()->json(['ApplicationCourse'=>$ApplicationCourse,'Document'=>$Document]);

}


public function course_edit(Request $request)
{

$item=LevelInformation:: whereid('1')->get();
$ApplicationCourse=ApplicationCourse::whereid($request->id)->whereuser_id(Auth::user()->id)->wherelevel_id($item[0]->id)->get();
$Document=ApplicationDocument::wherecourse_number($ApplicationCourse[0]->id)->get();
return response()->json(['ApplicationCourse'=>$ApplicationCourse,'Document'=>$Document]);

}


public function course_edits(Request $request,$id)
{

    //dd($request->all());
    $Document=ApplicationDocument::wherecourse_number($id)->get();



      //document upload
      if($request->hasfile('doc1'))
      {
          $doc1 = $request->file('doc1');
          $data = ApplicationDocument::find($Document[0]->id);
          $data->document_type_name='doc1';
          $name =$doc1[$i]->getClientOriginalName();
          $filename = time().$name;
          $doc1[$i]->move('documnet/',$filename);
          $data->document_file =  $filename;
          $data->save();
      }

      if($request->hasfile('doc2'))
      {
        $doc2 = $request->file('doc2');
        $data = ApplicationDocument::find($Document[2]->id);
         $data->document_type_name='doc2';
        $doc2 = $request->file('doc2');
        $name =$doc2[$i]->getClientOriginalName();
        $filename = time().$name;
        $doc2[$i]->move('documnet/',$filename);
        $data->document_file =  $filename;
        $data->save();
      }
      if($request->hasfile('doc3'))
      {
        $doc3 = $request->file('doc3');
        $data = ApplicationDocument::find($Document[3]->id);
        $data->document_type_name='doc3';
       $img = $request->file('doc3');
       $name =$doc3[$i]->getClientOriginalName();
       $filename = time().$name;
       $doc3[$i]->move('documnet/',$filename);
       $data->document_file =  $filename;
       $data->save();

      }


      $file = ApplicationCourse::find($id);
      $file->course_name=$request->Course_Names;
      $file->user_id=Auth::user()->id;
      $file->country=Auth::user()->country;
      $file->eligibility=$request->Eligibilitys;
      $file->mode_of_course=$request->Mode_Of_Courses;
      $file->course_brief=$request->Payment_Statuss;
      $file->valid_from=$request->created_at;
      $file->save();

      return back();




}

}
