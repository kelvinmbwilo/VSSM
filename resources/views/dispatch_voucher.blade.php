<?php
/**
 * Created by PhpStorm.
 * User: kelvin
 * Date: 9/21/16
 * Time: 5:42 PM
 */
?>
<div id="exportthis">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="col-sm-2">
            <img src="<?php echo asset('img/logo1.jpg'); ?>" style="height: 100px;width: 100px">
        </div>
        <div class="col-sm-8">
            <h3 class="text-center" translate="app.title"></h3>
            <h4 class="text-center" translate="app.voucher_title"></h4>
            <hr>
        </div>
        <div class="col-sm-2">
            <img src="<?php echo asset('img/logo.png'); ?>" style="height: 100px;width: 100px">
        </div>
    </div>
    <div class="col-sm-10 col-sm-offset-1 text-center">
        {{--{{ $name }} {{ $second_name }}--}}
        {{--<div barcode-generator="{{ newItem.voucher_no  }}" style="width: 250px; margin: 0 auto;height: 120px"></div>--}}
        {{--{{ newItem.voucher_no  }}--}}
    {{--</div>--}}
    {{--<div class="col-sm-10 col-sm-offset-1 text-center">--}}
        {{--<h4 translate="labels.dispatch_date">{{ newItem.dispatch_date  }}</h4>--}}
    {{--</div>--}}
    {{--<div class="col-sm-10 col-sm-offset-1" style="margin-top: 10px;margin-bottom: 20px">--}}
        {{--<div class="col-sm-4"><h4 translate="labels.entered_by" translate-value-name="{{ logedInUserName }}"> </h4></div>--}}
        {{--<div class="col-sm-4"><h4 translate="labels.issued_to" translate-value-name="{{ getRecipientName(newItem.recipients) }}"></h4></div>--}}
        {{--<div class="col-sm-4"><h4 translate="labels.transport_mode1" translate-value-name="{{ getTransportName(newItem.transport_mode) }}"></h4></div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-10 col-sm-offset-1">--}}
        {{--<table class="table table-striped table-bordered" >--}}
            {{--<thead>--}}
            {{--<tr style="background-color: greenyellow">--}}
                {{--<th >Sr</th>--}}
                {{--<th translate="labels.product"></th>--}}
                {{--<th translate="labels.manufacture"></th>--}}
                {{--<th translate="labels.lot_number"></th>--}}
                {{--<th translate="labels.expired_date"></th>--}}
                {{--<th translate="labels.doses"></th>--}}
                {{--<th translate="labels.vials"></th>--}}
                {{--<th translate="labels.t_price"></th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--<tr ng-repeat="item in newItem.items" ng-hide="deletedItem[item.id]">--}}
                {{--<td>{{ $index+1 }}</td>--}}
                {{--<td>{{ item.item }}</td>--}}
                {{--<td>{{ getManufactureName(item.manufacture_id) }}</td>--}}
                {{--<td>{{ item.lot_number }}</td>--}}
                {{--<td>{{ item.expired_date  }}</td>--}}
                {{--<td>{{ item.doses }}</td>--}}
                {{--<td>{{ item.vials }}</td>--}}
                {{--<td>{{ main_currency }} {{ item.u_price * item.doses }}</td>--}}
            {{--</tr>--}}
            {{--</tbody>--}}
        {{--</table>--}}
    {{--</div>--}}
    {{--<div class="col-sm-10 col-sm-offset-1">--}}
        {{--<h4 translate="labels.total_price" translate-value-price="{{ getTotalPrice() }}" translate-value-currency="{{ main_currency }}"></h4>--}}
        {{--<div class="col-sm-4">--}}
            {{--<h5 translate="labels.issued"></h5>--}}
            {{--<h5 translate="labels.name_signature"></h5>--}}
            {{--<h5 translate="labels.sign"></h5>--}}
        {{--</div>--}}
        {{--<div class="col-sm-4"></div>--}}
        {{--<div class="col-sm-4">--}}
            {{--<h5 translate="labels.receiver"></h5>--}}
            {{--<h5 translate="labels.name_signature"></h5>--}}
            {{--<h5 translate="labels.sign"></h5>--}}
        {{--</div>--}}
    {{--</div>--}}
</div>