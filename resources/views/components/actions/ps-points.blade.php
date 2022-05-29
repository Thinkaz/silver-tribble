@props(['action' => []])

<div>
    <div class="form-group">
        <label for="exampleInputEmail1">Amount</label>
        <input type="number" class="form-control" name="actions[ps_points][amount]" placeholder="50"
               value="{{$action['amount'] ?? 0}}">
    </div>
</div>