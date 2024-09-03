<?php

namespace Techalyst\Kanban\Controllers;

use App\Http\Controllers\Controller;
use Techalyst\Kanban\Models\TaskBoard;
use Techalyst\Kanban\Models\TaskItem;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use SEOMeta;
use SEO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function boardView(Request $request)
    {
        if($request->has('masterData')){
            $board_results = array();
            $boards = TaskBoard::with('activeitems')->orderBy('board_order','ASC')->get();

            foreach ($boards as $board){
                $itemArr = array();
                foreach ($board->activeitems as $item){
                    $itemArr[] = [
                        'id' => $item->item_prefixed_id,
                        'title' => view('kanban::task.item')->with('model',$item)->render(),
                        'taskname' => $item->taskname,
                        'remark' => $item->remark,
                        'name' => $item->name,
                        'gender' => $item->gender,
                        'country' => $item->country,
                        'agency' => $item->agency,
                        'position' => $item->position,
                        'phone' => $item->phone,
                        'email' => $item->email,
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
                'boards' => $board_results,
                'country_options' => TaskItem::select('country')->groupBy('country')->pluck('country'),
                'agency_options' => TaskItem::select('agency')->groupBy('agency')->pluck('agency'),
                'position_options' => TaskItem::select('position')->groupBy('position')->pluck('position')
            ]);

            return response()->json($data);
        }else{

            SEO::setTitle('A Beautiful Kanban Task Board for Laravel and Vue.js | Suitable for building Scrum Task Tracker');
            SEO::setDescription('A Beautiful Kanban Task Board for Laravel and Vue.js | Suitable for building Scrum Task Tracker');
            SEO::setCanonical(url()->current());

            SEO::opengraph()->setTitle('A Beautiful Kanban Task Board for Laravel and Vue.js | Suitable for building Scrum Task Tracker');
            SEO::opengraph()->setDescription('A Beautiful Kanban Task Board for Laravel and Vue.js | Suitable for building Scrum Task Tracker');
            SEO::opengraph()->setUrl(url()->current());
            SEO::opengraph()->addProperty('type', 'article');
            SEO::opengraph()->addProperty('site_name', config('app.name'));
            SEO::opengraph()->addProperty('article:author', 'Akram Wahid');
            SEO::opengraph()->addProperty('article:published_time', '2019-09-12');

            SEO::twitter()->addValue('card','summary');
            SEO::twitter()->setSite(config('social.media.twitter'));
            SEO::twitter()->setTitle('A Beautiful Kanban Task Board for Laravel and Vue.js | Suitable for building Scrum Task Tracker');
            SEO::twitter()->setDescription('A Beautiful Kanban Task Board for Laravel and Vue.js | Suitable for building Scrum Task Tracker');

            return view('kanban::task.board');
        }
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
        return response()->json($response);
    }

    public function createItem(Request $request)
    {
        $rules = [
            'boardId' => 'required|exists:'.config('kanban.database_connection').'.kanban_task_board,id,deleted_at,NULL',
            'taskname' => 'required|max:255',
            'remark' => 'required|max:2000',
            'name' => 'required|max:255',
            'gender' => 'required',
            'phone' => 'max:20',
            'email' => 'max:255',
        ];

        $messages = [
            'boardId.required' => 'Please select a board in which you want to assign the candidate.',
            'boardId.exists' => 'Not a valid value selected for the board.',
            'taskname.required' => 'Task Name is a required field.',
            'taskname.max' => 'Task Name cannot exceed more than 255 characters.',
            'remark.required' => 'Task Description is a required field.',
            'remark.max' => 'Task Description cannot exceed more than 2000 characters.',
            'name.required' => 'Please write the Customer Name.',
            'name.max' => 'Customer Name cannot exceed more than 255 characters.',
            'gender.required' => 'Please select the Customer Gender.',
            'phone.max' => 'Phone cannot exceed more than 20 digits.',
            'email.max' => 'Email cannot exceed more than 255 characters.',
        ];

        $this->validate($request,$rules,$messages);

        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $model = new TaskItem();
            $model->name = $request->input('name');
            $model->gender = $request->input('gender');
            $model->taskname = $request->input('taskname');
            $model->remark = $request->input('remark');
            $model->phone = $request->input('phone');
            $model->email = $request->input('email');
            $model->save();

            $model->item_prefixed_id = 'item'.$model->id;
            $model->rendered_html = view('kanban::task.item')->with('model',$model)->render();
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
        return response()->json($response);
    }

    public function editItem(Request $request)
    {
        $rules = [
            'id' => 'required|exists:'.config('kanban.database_connection').'.kanban_task_item,id,deleted_at,NULL',
            'taskname' => 'required|max:255',
            'remark' => 'required|max:2000',
            'name' => 'required|max:255',
            'gender' => 'required',
            'phone' => 'max:20',
            'email' => 'max:255',
        ];

        $messages = [
            'id.required' => 'Something went wrong!, please refresh the page and try again.',
            'id.exists' => 'Something went wrong!, please refresh the page and try again.',
            'taskname.required' => 'Task Name is a required field.',
            'taskname.max' => 'Task Name cannot exceed more than 255 characters.',
            'remark.required' => 'Task Description is a required field.',
            'remark.max' => 'Task Description cannot exceed more than 2000 characters.',
            'name.required' => 'Please write the candidate name in the Name field.',
            'name.max' => 'Name cannot exceed more than 255 characters.',
            'gender.required' => 'Please select the Candidate Gender.',
            'phone.max' => 'Phone cannot exceed more than 20 digits.',
            'email.max' => 'Email cannot exceed more than 255 characters.',
        ];

        $this->validate($request,$rules,$messages);

        try{
            DB::connection(config('kanban.database_connection'))->beginTransaction();

            $model = TaskItem::findOrFail($request->input('id'));
            $model->name = $request->input('name');
            $model->gender = $request->input('gender');
            $model->taskname = $request->input('taskname');
            $model->remark = $request->input('remark');
            $model->phone = $request->input('phone');
            $model->email = $request->input('email');
            $model->rendered_html = view('kanban::task.item')->with('model',$model)->render();
            $model->save();

            DB::connection(config('kanban.database_connection'))->commit();

        }catch (\Exception $ex){
            DB::connection(config('kanban.database_connection'))->rollback();

            $response = [
                "errors" =>  array(['Something Went Wrong, Please Try Again!. Error -> '.$ex->getMessage()])
            ];
            return response()->json($response,422);
        }

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

        $viewData =  view('kanban::task.timeline')->with('item',$item)->with('boards',$item->boards()->orderBy('pivot_entered_at', 'ASC')->get())->render();
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
