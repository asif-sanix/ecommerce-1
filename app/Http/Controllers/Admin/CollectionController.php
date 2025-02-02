<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Collection\CollectionCollection;

class CollectionController extends Controller
{
    
    public function index(Request $request)
    {
       
        if ($request->ajax()) {
            $datas = Collection::orderBy('created_at','desc')->select(['id','title','slug','image','created_at','status']);
            $search = $request->search['value'];

            if ($search) {
                $datas->where('name', 'like', '%'.$search.'%');
                $datas->orWhere('slug', 'like', '%'.$search.'%');
              
            }
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();
            return response()->json(new CollectionCollection($datas));
           
        }

        return view('admin.collection.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $collections = Collection::select('id', 'title')->pluck('title', 'id');
        return view('admin.collection.create', compact('collections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'title'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' =>'nullable',
            'status'=>'required|integer',
            'published_date'=>'required',
        ]);
        
        $collection = new Collection;
        $collection->title = $request->title;
        $collection->body = $request->description;
        $collection->parent = $request->parent;
        $collection->status = $request->status?1:0;
        $collection->published_at = Carbon::parse($request->published_date)->format('Y-m-d');
       
        $collection->meta_title = $request->meta_title??$request->title;
        $collection->meta_description = $request->meta_description??$request->description;


        if($request->hasFile('image')){
            $image_name = time().".".$request->file('image')->getClientOriginalExtension();
            $image = $request->file('image')->storeAs('collections', $image_name);
            $collection->image = 'storage/'.$image;
        }  

        if($collection->save()){ 
            return redirect()->route('admin.collection.index')->with(['class'=>'success','message'=>'Collection Created successfully.']);
        }

        return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Collection $collection)
    {
        return view('admin.collection.create',compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Collection $collection)
    {
        $collections = Collection::select('id', 'title')->pluck('title', 'id');
        return view('admin.collection.edit',compact('collection','collections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $this->validate($request,[
            'name'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'description' =>'nullable',
            'status'=>'required|integer',
        ]);
        
        $collection = Collection::find($id);
        $collection->name = $request->name;
        $collection->body = $request->description;
        $collection->status = $request->status;

        // $collection->name = $request->name;
        // $collection->meta_title = $request->metaTitle??$request->name;
        // $collection->meta_keywords = $request->metaKeywords??$request->name;
        // $collection->meta_description = $request->metaDescription??$request->body;
        // $collection->status = $request->status;

        if($request->hasFile('image')){
            $image_name = time().".".$request->file('image')->getClientOriginalExtension();
            $image = $request->file('image')->storeAs('categories', $image_name);
            $collection->image = 'storage/'.$image;
        }  

        if($collection->save()){ 
            return redirect()->route('admin.collection.index')->with(['class'=>'success','message'=>'Collection Created successfully.']);
            return view('admin.collection.edit',compact('Collection'));
        }

        return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request, $id)
    {
        //return "ok";
        $catId = $id; 
        if(Collection::where('id',$id)->delete()){
            $cat1 = Collection::select('id')->where('parent',$catId);
            if ($cat1->count()) {
               $cat2 = Collection::select('id')->whereIn('parent',$cat1->get())->delete();
            }
            $cat1->delete();
            //  $logMessage= array(
            //     'user_id'=>auth('admin')->user()->id,
            //     'table'=>'Collection',
            //     'table_id'=>$id
            // );
            
            return redirect()->route('admin.'.request()->segment(2).'.index')->with(['message'=>'Collection deleted successfully ...', 'class'=>'success']);  
        }
        return redirect()->back()->with(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }
}
