import './bootstrap';

window.Echo.channel("public-channel").listen("MessageSent", (e) => {
    console.log("📡 Event received:", e.message);
});
