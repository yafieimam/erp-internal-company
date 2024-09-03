<div class="tab-pane fade" id="waiting" style="padding-right: 25px; padding-left: 25px;">
  @forelse($waiting as $data)
  <a href="#">
    <div class="col-sm-12 border" style="padding: 0; margin-bottom: 20px; display: block;">
      <div class="info-orders" style="background-color: #fdffdb;">Waiting For Confirmation</div>
      <ul>
        <li><a href=""><i class="fa fa-calendar-o"></i>{{ $data->tanggal_order }}</a></li>
        <li><a href=""><i class="fa fa-tag"></i>{{ $data->nomor_order_receipt }}</a></li>
      </ul>
      <hr>
      <div class="list-products">
        <p class="produk-orders" align="justify">{{ $data->produk }}</p>
        <p class="total-orders">Total Pembayaran:</p>
        <p style="color: #ff930f;">Rp {{ number_format($data->total,2,',','.') }}</p>
      </div>
    </div>
  </a>
  @empty
  <h2>No Data</h2>
  @endforelse

  {{ $waiting->links() }}

</div>

<div class="tab-pane fade" id="process" style="padding-right: 25px; padding-left: 25px;">
  @forelse($process as $data)
  <div class="col-sm-12 border" style="padding: 0; margin-bottom: 20px;">
    <div class="info-orders" style="background-color: #fdffdb;">Order Processed</div>
    <ul>
      <li><a href=""><i class="fa fa-calendar-o"></i>{{ $data->tanggal_order }}</a></li>
      <li><a href=""><i class="fa fa-tag"></i>{{ $data->nomor_order_receipt }}</a></li>
    </ul>
    <hr>
    <div class="list-products">
      <p class="produk-orders" align="justify">{{ $data->produk }}</p>
      <p class="total-orders">Total Pembayaran:</p>
      <p style="color: #ff930f;">Rp {{ number_format($data->total,2,',','.') }}</p>
    </div>
  </div>
  @empty
  <h2>No Data</h2>
  @endforelse

  {{ $process->links() }}
</div>