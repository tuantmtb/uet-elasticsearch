<input type="hidden" name="id">
<div class="editable-address">
    <label>
        <span>Tên: </span>
        <input type="text" name="name" class="form-control input-small" style="width: 500px !important;">
    </label>
</div>
<div class="editable-address">
    <label>
        <span>Email: </span>
        <input type="text" name="email" class="form-control input-small" style="width: 500px !important;">
    </label>
</div>
<div class="editable-address">
    <label>
        <span>Cơ quan: </span>
        {!! Form::select('organize', $organizes, null, ['class' => 'form-control input-small', 'style' => 'width: 500px !important']) !!}
    </label>
</div>