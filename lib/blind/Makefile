build:
	cargo build --target=wasm32-unknown-unknown --release
	wasm-bindgen target/wasm32-unknown-unknown/release/signature.wasm --out-dir  ../../public/static/wasm/ --target web
