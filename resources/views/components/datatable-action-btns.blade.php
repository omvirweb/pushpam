@foreach ($actionBtns as $actionBtn)
<a @if(isset($actionBtn['route'])) href="{{ $actionBtn['route'] }}" @else href="javascript:void(0);" @endif class="btn btn-sm btn-clean btn-icon btn-icon-md {{ @$actionBtn['class']}}" @if(isset($actionBtn['id'])) id="{{$actionBtn['id']}}" @endif @if(isset($actionBtn['data'])) @foreach ($actionBtn['data'] as $key=> $actionBtnData)
    {{
        "data-".$key ."=".$actionBtnData.""
    }}
    @endforeach
    @endif
    @if(isset($actionBtn['target'])) target="{{$actionBtn['target']}}" @endif>
    @if(isset($actionBtn['type']))
    @if($actionBtn['type']== 'show')
    <i class="la la-eye"></i>
    @endif
    @if($actionBtn['type']== 'edit')
    <i class="la la-edit"></i>
    @endif
    @if($actionBtn['type']== 'destroy')
    <i class="la la-trash"></i>
    @endif
    @endif
    @if(isset($actionBtn['text']))
    {{ " " . $actionBtn['text'] }}
    @endif
</a>
@endforeach
