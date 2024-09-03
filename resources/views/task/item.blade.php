<div class="hpanel hred hbgnavyblue ">

    <div class="panel-body" style="padding: 10px;">
        <div class="row">
            <div class="col-md-7">
                <h4>{{ $model->title_name }}</h4>
            </div>
            <div class="col-md-5">
                <div class="float-e-margins pull-right">
                    <button type="button" class="btn btn-xs btn-success edit-item" id="edit-{{ $model->id }}"><i class="fa fa-pencil"></i> </button>
                    <button type="button" class="btn btn-xs btn-danger2 delete-item" id="delete-{{ $model->id }}"><i class="fa fa-trash-o "></i> </button>
                    <button type="button" class="btn btn-xs btn-primary2 audit-item" id="audit-{{ $model->id }}"><i class="fa fa-history "></i> </button>
                </div>
            </div>
        </div>
        <p>
            @if($model->deadline_date < date('Y-m-d'))
            <div style="color:#f24b4b;"><h5><span>Deadline Date : </span> <strong>{{ $model->deadline_date }}</strong></h5></div>
            @elseif($model->deadline_date == date('Y-m-d'))
            <div style="color:#dede54;"><h5><span>Deadline Date : </span> <strong>{{ $model->deadline_date }}</strong></h5></div>
            @else
            <div><h5><span>Deadline Date : </span> <strong>{{ $model->deadline_date }}</strong></h5></div>
            @endif
        </p>
        <br>
        <p>
            {{ $model->deskripsi }}
        </p>

    </div>
</div>
