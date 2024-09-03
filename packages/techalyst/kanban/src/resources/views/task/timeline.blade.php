<div class="row">
    <div class="col-md-12">
        <div class="hpanel">
            <div class="v-timeline vertical-container animate-panel" data-child="vertical-timeline-block"
                 data-delay="1">
                @if($boards->count())
                    @foreach($boards as $board)
                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div class="vertical-timeline-content">
                                <div class="p-sm">
                                    <div style="display: inline;" class="clearfix">
                                        <div class="row">
                                            <div class="col-md-2" style="padding-left: 15px;padding-right: 5px;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @if($item->gender == 'Female')
                                                            <img alt="logo" class="img-circled" src="/img/woman.png"
                                                                 style="width: 40px;height: auto">
                                                        @else
                                                            <img alt="logo" class="img-circled" src="/img/man.png"
                                                                 style="width: 40px;height: auto">
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div
                                                            class="text-muted font-bold m-b-sm">{{ $item->gender }}</div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md-10" style="padding-left: 10px;padding-right: 5px;">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="text-muted font-bold m-b-xs pull-left text-right">
                                                            <span class="text-success">Name</span> : {{ $item->name }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="text-muted font-bold m-b-xs pull-left text-right">
                                                            <span class="text-success">Entered at</span>
                                                            : {{ $board->pivot->entered_at }}</div>
                                                    </div>

                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="text-muted font-bold m-b-xs pull-left text-right">
                                                            <span class="text-success">Left at</span>
                                                            : {{ $board->pivot->left_at ? : '-' }}</div>
                                                    </div>

                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="text-muted font-bold m-b-xs pull-left text-right">
                                                            <span class="text-success">Time Elapsed</span>
                                                            : {{ \App\Helpers\KanbanHelper::stringDateDiff($board->pivot->entered_at,$board->pivot->left_at) }}
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                    </div>
                                </div>
                                <div class="panel-footer text-info" style="font-size: 18px;">
                                    {{ $board->title }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
