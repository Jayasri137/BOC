<tbody>
                                    
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <img src="{{ $course?->mediaPath }}" alt="image" width="80"
                                                            height="80" style="border-radius: 16px; object-fit: cover">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-pera">
                                                        <a href="{{ route('course.edit', $course->id) }}"
                                                            class="priceDis">{{ $course?->title }}</a>
                                                    </div>
                                                </td>
                                                <td>{{ $course->category?->title }}</td>
                                                <td>
                                                    @php
                                                        $price = $course->price && $course->regular_price
                                                                ? $course->price
                                                                : $course->regular_price;
                                                    @endphp
                                                    @if ($app_setting['currency_position'] == 'Left')
                                                        {{ $app_setting['currency_symbol'] }}{{ $price }}
                                                    @else
                                                        {{ $price }}{{ $app_setting['currency_symbol'] }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <a class="action-icon btn-outline-primary d-flex justify-content-center align-items-center border border-primary p-2 rounded-3"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="{{ __('View Chapter') }}"
                                                        href="{{ route('chapter.index', $course->id) }}">
                                                        {{ __('View Chapter') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <h5 class="text-danger text-center m-0">{{ __('No Course Available') }}</h5>
                                                </td>
                                            </tr>
                                   
                                    </tbody>



                                    {{ $courses->links() }}