<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Slider\SliderCollection;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $datas = Slider::orderBy('created_at','desc')->select(['id','title','body','button_text','button_link','status','image','created_at']);


            $search = $request->search['value'];
            if ($search) {
                $datas->where('title', 'like', '%'.$search.'%');
                $datas->orWhere('body', 'like', '%'.$search.'%');
              
            }
            $records = $datas->limit($request->length)->offset($request->start)->get();
            $request->merge(['recordsTotal' => $datas->count()]);
            return response()->json(new SliderCollection($records));
           
        }

        return view('admin.slider.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request )
    {
        return view('admin.slider.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Employee $employee )
    {   
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function store(Request $request) {

            $this->validate($request,[
                // 'title'=>'required',
                // 'sub_title'=>'required',
                // 'button_text'=>'required',
                // 'button_link'=>'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:4000',    
            ]);

            $slider = new Slider;
          
            $slider->title = $request->title;
            $slider->body = $request->description;
            $slider->button_text = $request->button_text;
            $slider->button_link = $request->button_link;
            $slider->status = $request->status;


            if($request->hasFile('image')){
                $image_name = time().".".$request->file('image')->getClientOriginalExtension();
                $image = $request->file('image')->storeAs('slider', $image_name);
                $slider->image = 'storage/'.$image;
            }  

            if($slider->save()){ 
                return redirect()->route('admin.slider.index')->with(['class'=>'success','message'=>'Slider Created successfully.']);
            }

            return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
        }
        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Slider $slider)
    {
        return view('admin.slider.edit', compact('slider')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $this->validate($request,[
                // 'title'=>'required',
                // 'sub_title'=>'required',
                // 'button_text'=>'required',
                // 'button_link'=>'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4000',    
            ]);
          
            $slider->title = $request->title;
            $slider->body = $request->description;
            $slider->button_text = $request->button_text;
            $slider->button_link = $request->button_link;
            $slider->status = $request->status;


            if($request->hasFile('image')){
                $image_name = time().".".$request->file('image')->getClientOriginalExtension();
                $image = $request->file('image')->storeAs('slider', $image_name);
                $slider->image = 'storage/'.$image;
            }  

            if($slider->save()){ 
                return redirect()->route('admin.slider.index')->with(['class'=>'success','message'=>'Slider Updated successfully.']);
            }

            return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Slider $slider)
    {
        if($slider->delete()){
            
            return response()->json(['message'=>'Admin deleted successfully ...', 'class'=>'success']);  
        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }
}
