<div class="hpanel hred hbgnavyblue ">
    <div class="panel-body" style="padding: 10px;">
        <div class="row">
            <div class="col-md-7">
                <h5>Name: {{ $model->name }}</h5>
                @if($model->gender == 'Female')
                    <img alt="logo" class="img-circle m-b" src="/img/woman.png" style="width: 70px;height: auto">
                @else
                    <img alt="logo" class="img-circle " src="/img/man.png" style="width: 70px;height: auto">
                @endif
            </div>
            <div class="col-md-5">
                <div class="float-e-margins pull-right">
                    <button type="button" class="btn btn-xs btn-success edit-item" id="edit-{{ $model->id }}"><i class="fa fa-pencil"></i> </button>
                    <button type="button" class="btn btn-xs btn-danger2 delete-item" id="delete-{{ $model->id }}"><i class="fa fa-trash-o "></i> </button>
                    <button type="button" class="btn btn-xs btn-primary2 audit-item" id="audit-{{ $model->id }}"><i class="fa fa-history "></i> </button>
                </div>

                <div><span>Phone</span> <strong>{{ $model->phone }}</strong></div>
                <div><span>Email</span> <strong>{{ $model->email }}</strong></div>
            </div>
        </div>

        <div class="text-mutedd font-bold m-b-xs">Need: {{ $model->taskname }}</div>
        <p>
            {{ $model->remark }}
        </p>

    </div>
</div>
