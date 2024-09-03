<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskBoard;
use App\TaskItem;
use Illuminate\Validation\ValidationException;
use SEOMeta;
use SEO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Response;

class TaskController extends Controller
{	
	public function projectViewProduksi(){
		if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
	}

	public function projectViewSales(){
		if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
	}

    public function projectViewHRD(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
    }

	public function projectViewLogistik(){
		if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
	}

	public function projectViewTimbangan(){
		if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
	}

	public function projectViewWarehouse(){
		if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
	}

	public function projectViewLab(){
		if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
	}

    public function projectViewTeknik(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
    }

    public function projectViewRawMaterial(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
    }

    public function projectViewPA(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.project');
        }
    }

    public function projectViewPASales(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_sales');
        }
    }

    public function projectViewPAProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_produksi');
        }
    }

    public function projectViewPAHRD(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_hrd');
        }
    }

    public function projectViewPALogistik(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_logistik');
        }
    }

    public function projectViewPATimbangan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_timbangan');
        }
    }

    public function projectViewPAWarehouse(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_warehouse');
        }
    }

    public function projectViewPALab(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_lab');
        }
    }

    public function projectViewPATeknik(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_teknik');
        }
    }

    public function projectViewPARawMaterial(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('task.pa_project_raw_material');
        }
    }

    public function boardView(Request $request, $id)
    {
        if($request->has('masterData')){
            $board_results = array();
            $boards = TaskBoard::with('activeitems')->where('projectId', $id)->orderBy('board_order','ASC')->get();

            foreach ($boards as $board){
                $itemArr = array();
                foreach ($board->activeitems as $item){
                    $itemArr[] = [
                        'id' => $item->item_prefixed_id,
                        'title' => view('task.item')->with('model',$item)->render(),
                        'title_name' => $item->title_name,
                        'deskripsi' => $item->deskripsi,
                        'deadline_date' => $item->deadline_date,
                    ];
                }
                $board_results[] = [
                    'id' => $board->id,
                    'boardId' => $board->board_prefixed_id,
                    'title' => $board->title,
                    'item' => $itemArr
                ];
            }

            $data = collect([
                'boards' => $board_results
            ]);

            $project = DB::table('kanban_task_project')->where('id', $id)->first();

            return response()->json(["boards" => $data, "project" => $project]);
        }else{
        	$project = DB::table('kanban_task_project')->select('title', 'tipe_user')->where('id', $id)->first();
            if(Session::get('tipe_user') == 10){
                $tipe_user = 2;
            }else{
                $tipe_user = Session::get('tipe_user');
            }
        	if(($project->tipe_user == $tipe_user) || (Session::get('tipe_user') == 1) || (Session::get('tipe_user') == 19)){
	            SEO::setTitle(strtoupper($project->title) . ' - PT. DWI SELO GIRI MAS');
	            SEO::setDescription($project->title);
	            SEO::setCanonical(url()->current());

	            return view('task.board', ['id' => $id]);
	        }else{
	        	return redirect()->back()->with('alert','You Do Not Have an Authorization');
	        }
        }
    }

    public function createProject(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
    	$data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => Session::get('tipe_user'), 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

    	DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

    	$arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Membuat Project Baru No. ' . $data]);

    	return response()->json($arr);
    }

    public function createProjectPASales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 2, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Sales No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPAProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 3, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Produksi No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPAHRD(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 5, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk HRD No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPALogistik(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 6, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 6, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Logistik No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPATimbangan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 7, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 7, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Timbangan No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPAWarehouse(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 8, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 8, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Warehouse No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPALab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 9, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 9, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Lab No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPATeknik(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 16, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 16, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Teknik No. ' . $data]);

        return response()->json($arr);
    }

    public function createProjectPARawMaterial(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kanban_task_project')->insertGetId(['title' => $request->title, 'tipe_user' => 18, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        DB::table('kanban_task_project')->where('id', $data)->update(['project_prefixed_id' => 'project'.$data]);

        $arr = array('msg' => 'Data Successfully Stored', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => 18, 'action' => 'User ' . Session::get('id_user_admin') . ' / PA Membuat Project Baru untuk Raw Material No. ' . $data]);

        return response()->json($arr);
    }

    public function getProject(){
        if(Session::get("tipe_user") == 10 || Session::get("tipe_user") == 2){
            $data = DB::table("kanban_task_project")->where(function ($query) { $query->where('tipe_user', 2)->orWhere('tipe_user', 10); })->orderBy("created_at", "asc")->get();
        }else{
            $data = DB::table("kanban_task_project")->where("tipe_user", Session::get("tipe_user"))->orderBy("created_at", "asc")->get();
        }

    	return response()->json($data);
    }

    public function getProjectPASales(){
        $data = DB::table("kanban_task_project")->where(function ($query) { $query->where('tipe_user', 2)->orWhere('tipe_user', 10); })->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPAProduksi(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 3)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPAHRD(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 5)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPALogistik(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 6)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPATimbangan(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 7)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPAWarehouse(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 8)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPALab(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 9)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPATeknik(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 16)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function getProjectPARawMaterial(){
        $data = DB::table("kanban_task_project")->where('tipe_user', 18)->orderBy("created_at", "asc")->get();

        return response()->json($data);
    }

    public function createBoard(Request $request)
    {
        $rules = [
            'boarditems' => 'required',
            'boarditems.*.title' => 'required'
        ];

        $messages = [
            'boarditems.required' => 'Please fill the column title to add one or more column.',
            'boarditems.*.title.required' => 'Please fill the column title to add one or more column.',
        ];

        $boarditems = json_decode($request->input('boarditems'),true);

        $data = array(
            'boarditems' => $boarditems
        );

        $validator = Validator::make($data,$rules,$messages);
        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $results = array();

        foreach ($boarditems as $item){
            $board = new TaskBoard();
            $board->projectId = $item['projectid'];
            $board->title = $item['title'];
            $board->save();
            $board->board_prefixed_id = 'board'.$board->id;
            $board->board_order = TaskBoard::count();
            $board->save();

            $results[] = [
                'id' => $board->id,
                'boardId' => $board->board_prefixed_id,
                'title' => $board->title,
                'item' => []
            ];

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Membuat Board Baru Pada Project ' . $item['projectid'] . ' Dengan No. ' . $board->id]);
        }

        return response()->json($results);
    }

    public function deleteBoard(Request $request)
    {
        $boardId = $request->input('boardIdPrefixed');
        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $board = TaskBoard::where('board_prefixed_id','=',$boardId)->first();
            $board->items()->sync([]);
            $board->delete();

            DB::connection(config('kanban.database_connection'))->commit();
        }catch (\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        $response = [
            "Status" => "Success",
            "Message" =>   'Column was deleted successfully.'
        ];

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Menghapus Board No. ' . $boardId]);

        return response()->json($response);
    }

    public function createItem(Request $request)
    {
        $rules = [
            'boardId' => 'required|exists:'.config('kanban.database_connection').'.kanban_task_board,id,deleted_at,NULL',
            'title_name' => 'required|max:255',
            'deskripsi' => 'required|max:2000',
            'deadline_date' => 'required',
        ];

        $messages = [
            'boardId.required' => 'Please select a board in which you want to assign the candidate.',
            'boardId.exists' => 'Not a valid value selected for the board.',
            'title_name.required' => 'Title is a required field.',
            'title_name.max' => 'Title cannot exceed more than 255 characters.',
            'deskripsi.required' => 'Description is a required field.',
            'deskripsi.max' => 'Description cannot exceed more than 2000 characters.',
            'deadline_date.required' => 'Please select the Deadline Date.',
        ];

        $this->validate($request,$rules,$messages);

        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $model = new TaskItem();
            $model->title_name = $request->input('title_name');
            $model->deskripsi = $request->input('deskripsi');
            $model->deadline_date = $request->input('deadline_date');
            $model->save();

            $model->item_prefixed_id = 'item'.$model->id;
            $model->rendered_html = view('task.item')->with('model',$model)->render();
            $model->save();

            $boardId = (int) $request->input('boardId');
            $board = TaskBoard::find($boardId);
            $board->items()->attach($model->id,['active' => 1, 'entered_at' => Carbon::now()->toDateTimeString(), 'item_order' => $board->activeitems->count() ]);

            DB::connection(config('kanban.database_connection'))->commit();
        }catch (\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Membuat Item No. ' . $model->id . ' pada Board No. ' . $boardId]);

        return response()->json($model->toArray());
    }

    public function moveItem(Request $request)
    {
        $rules = [
            'itemId' => 'required',
            'oldBoardId' => 'required',
            'newBoardId' => 'required',
        ];

        $messages = [
            'itemId.required' => 'Something went wrong, please try again.',
            'oldBoardId.required' => 'Something went wrong, please try again.',
            'newBoardId.required' => 'Something went wrong, please try again.',
        ];

        $this->validate($request,$rules,$messages);

        $itemId = $request->input('itemId');
        $oldBoardId = $request->input('oldBoardId');
        $newBoardId = $request->input('newBoardId');

        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $item = TaskItem::where('item_prefixed_id','=',$itemId)->first();
            $oldBoard = TaskBoard::where('board_prefixed_id','=',$oldBoardId)->first();
            $newBoard = TaskBoard::where('board_prefixed_id','=',$newBoardId)->first();

            DB::connection(config('kanban.database_connection'))->table('kanban_task_board_item')->where('itemId',$item->id)->where('active', 1)->update(['active' => 0, 'left_at' => Carbon::now()->toDateTimeString()]);

            $item->boards()->attach($newBoard->id,['active' => 1, 'entered_at' => Carbon::now()->toDateTimeString()]);

            DB::connection(config('kanban.database_connection'))->commit();
        }catch(\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        $response = [
            "message" => "You have successfully moved an item!."
        ];

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Memindahkan Item No. ' . $itemId . ' dari Board ' . $oldBoardId . ' ke Board ' . $newBoardId]);

        return response()->json($response);
    }

    public function editItem(Request $request)
    {
        $rules = [
            'id' => 'required|exists:'.config('kanban.database_connection').'.kanban_task_item,id,deleted_at,NULL',
            'title_name' => 'required|max:255',
            'deskripsi' => 'required|max:2000',
            'deadline_date' => 'required',
        ];

        $messages = [
            'id.required' => 'Something went wrong!, please refresh the page and try again.',
            'id.exists' => 'Something went wrong!, please refresh the page and try again.',
            'title_name.required' => 'Title is a required field.',
            'title_name.max' => 'Title cannot exceed more than 255 characters.',
            'deskripsi.required' => 'Description is a required field.',
            'deskripsi.max' => 'Description cannot exceed more than 2000 characters.',
            'deadline_date.required' => 'Please select the Deadline Date.',
        ];

        $this->validate($request,$rules,$messages);

        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $model = TaskItem::findOrFail($request->input('id'));
            $model->title_name = $request->input('title_name');
            $model->deskripsi = $request->input('deskripsi');
            $model->deadline_date = $request->input('deadline_date');
            $model->rendered_html = view('task.item')->with('model',$model)->render();
            $model->save();

            DB::connection(config('kanban.database_connection'))->commit();

        }catch (\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Mengedit Item No. ' . $request->input('id')]);

        return response()->json($model->toArray());
    }

    public function deleteItem(Request $request)
    {
        $id = (int) $request->input('id');
        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $item = TaskItem::findOrFail($id);
            $item->boards()->sync([]);
            $item->delete();

            DB::connection(config('kanban.database_connection'))->commit();
        }catch (\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        $response = [
            "Status" => "Success",
            "Message" =>   'Item was deleted from the board successfully'
        ];

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_project')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'tipe_user' => Session::get('tipe_user'), 'action' => 'User ' . Session::get('id_user_admin') . ' Menghapus Item No. ' . $id]);

        return response()->json($response);
    }

    public function auditItem(Request $request)
    {
        $id = (int) $request->input('id');

        try{
            $item = TaskItem::with('boards')->findOrFail($id);
        }catch (\Exception $ex){
            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        $viewData =  view('task.timeline')->with('item',$item)->with('boards',$item->boards()->orderBy('pivot_entered_at', 'ASC')->get())->render();
        $response = [
            "Status" => "Success",
            "Message" =>   $viewData
        ];
        return response()->json($response);
    }

    public function reorderBoard(Request $request)
    {
        $boardSorted = json_decode($request->input('boardSorted'),true);
        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            foreach ($boardSorted as $boardObj){
                $board = TaskBoard::where('board_prefixed_id','=',$boardObj['id'])->first();
                $board->board_order = $boardObj['order'];
                $board->save();
            }

            DB::connection(config('kanban.database_connection'))->commit();
        }catch(\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        $response = [
            "message" => "You have successfully reordered board!."
        ];
        return response()->json($response);
    }

    public function reorderBoardItem(Request $request)
    {
        $itemSorted = json_decode($request->input('itemSorted'),true);
        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            foreach ($itemSorted as $itemObj){
                $item = TaskItem::where('item_prefixed_id','=',$itemObj['id'])->first();
                DB::connection(config('kanban.database_connection'))->table('kanban_task_board_item')->where('itemId',$item->id)->where('active', 1)->update(['item_order' => $itemObj['order']]);

            }

            DB::connection(config('kanban.database_connection'))->commit();
        }catch(\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

        $response = [
            "message" => "You have successfully reordered item!."
        ];
        return response()->json($response);
    }
}
