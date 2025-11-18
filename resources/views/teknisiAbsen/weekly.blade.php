@extends('layouts.teknisi')

@section('title', 'Dashboard Teknisi')

@section('content')
<main class="app-main">
    <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Jadwal Teknisi</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jadwal Teknisi</li>
          </ol>
        </div>
      </div>
    </div>
  </div>


    <div class="app-content">
        <div class="container-fluid">


            {{-- Tabel jadwal mingguan --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Mingguan {{ $user->name }}
                    </h4>
                    <span class="fw-light">
                        {{ $startOfWeek->translatedFormat('d M') }} - {{ $endOfWeek->translatedFormat('d M Y') }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-center align-middle mb-0">
                            <thead class="table-dark table-responsive">
                                <tr>
                                    @foreach (range(0, 6) as $i)
                                        @php $date = $startOfWeek->copy()->addDays($i); @endphp
                                        <th>
                                            <div class="fw-bold">{{ $date->translatedFormat('D') }}</div>
                                            <small class="fw-normal">{{ $date->translatedFormat('d') }}</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach (range(0, 6) as $i)
                                        @php
                                            $dateString = $startOfWeek->copy()->addDays($i)->toDateString();
                                            $schedule = $schedules->get($dateString);
                                        @endphp
                                        <td>
                                            @if ($schedule && $schedule->shift)
                                                <span class="badge bg-{{ 
                                                    $schedule->shift->id == 1 ? 'primary' : 
                                                    ($schedule->shift->id == 2 ? 'success' : 'info') 
                                                }}">
                                                    {{ $schedule->shift->name }}
                                                </span>
                                                <br>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    {{ substr($schedule->shift->start_time, 0, 5) }} - {{ substr($schedule->shift->end_time, 0, 5) }}
                                                </small>
                                            @elseif($schedule && !$schedule->shift)
                                                <span class="badge bg-warning text-dark">OFF</span>
                                            @else
                                                <span class="text-muted badge bg-danger">LIBUR</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection