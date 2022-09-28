<form method="post" action="{{route('portfolio.comments', ['id' => $item->id])}}" id="form-update-{{$item->id}}">
    <div class="modal fade" id="addCommentsModal-{{$item->id}}" tabindex="-1" aria-labelledby="addCommentsModalLabel"
        data-portfolio-id="{{$item->id}}"
        data-last-comment="{{$item->comentarios->sortByDesc('id')->first()->id ?? null}}"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Comments <b id="row-number">#</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="item">Comments</label>
                        <textarea name="comments" id="comments" class="form-control"></textarea>
                    </div>

                    <div class="comments-area">
                        @isset($item->comentarios)
                            @foreach($item->comentarios as $comentario)
                                @if($comentario->id_addressbook == 1)
                                    <div class="my-comment-container">
                                        <div class="comment-balloon">
                                            <div style="width: 100%; height: 30px;"> 
                                                <div style="float: left;">
                                                    <b>{{$comentario->usuario->email}}</b>
                                                </div>
                                                <div style="float: right; font-size: 11px; padding-top: 5px;">
                                                    <i class="fa fa-clock"></i> &nbsp; {{ $comentario->created_at->diffForHumans()}}
                                                </div>
                                            </div>
                                            <div>
                                                <p>{{$comentario->comentario}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="their-comment-container">
                                        <div class="comment-balloon">
                                            <div style="width: 100%; height: 30px;"> 
                                                <div style="float: left;">
                                                    <b>{{$comentario->usuario->email}}</b>
                                                </div>
                                                <div style="float: right; font-size: 11px; padding-top: 5px;">
                                                    <i class="fa fa-clock"></i> &nbsp; {{ $comentario->created_at->diffForHumans()}}
                                                </div>
                                            </div>
                                            <div>
                                                <p>{{$comentario->comentario}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endisset
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send comments</button>
                </div>
            </div>
        </div>
    </div>
</form>