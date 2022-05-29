@props(['action' => []])

<div>
    <div class="form-group">
        <label for="exampleInputEmail1">Amount</label>
        <input type="number" class="form-control" name="actions[pulsar][amount]" placeholder="20"
               value="{{$action['amount'] ?? 0}}">
    </div>
</div>
