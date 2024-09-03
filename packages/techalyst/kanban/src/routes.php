<?php
Route::group(['prefix' => 'techalyst','middleware' => ['web']], function () {
    Route::group(['prefix' => 'kanban'], function () {

        Route::get('/board',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@boardView',
        ]);

        Route::post('/create/board',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@createBoard',
        ]);

        Route::post('/delete/board',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@deleteBoard',
        ]);

        Route::post('/create/item',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@createItem',
        ]);

        Route::post('/move/item',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@moveItem',
        ]);

        Route::post('/edit/item',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@editItem',
        ]);

        Route::post('/delete/item',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@deleteItem',
        ]);

        Route::post('/audit/item',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@auditItem',
        ]);

        Route::post('/reorder/board',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@reorderBoard',
        ]);

        Route::post('/reorder/item',[
            'uses' => 'Techalyst\Kanban\Controllers\TaskController@reorderBoardItem',
        ]);
    });
});
