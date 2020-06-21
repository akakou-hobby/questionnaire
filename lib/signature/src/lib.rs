#[no_mangle]

pub extern "C" fn hoge(v: f64) -> f64 {
    v + 1.0
}
