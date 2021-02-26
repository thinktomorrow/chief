/**
 * Lightweight eventbus implementation
 * based on the repo: https://github.com/PierfrancescoSoffritti/light-event-bus.js
 */

const subscriptions = {};
const getNextUniqueId = getIdGenerator();

function subscribe(event, callback) {
    const id = getNextUniqueId();

    if (!subscriptions[event]) subscriptions[event] = {};

    subscriptions[event][id] = callback;

    return {
        unsubscribe: () => {
            delete subscriptions[event][id];
            if (Object.keys(subscriptions[event]).length === 0) delete subscriptions[event];
        },
    };
}

function publish(event, arg) {
    if (!subscriptions[event]) return;

    Object.keys(subscriptions[event]).forEach((key) => subscriptions[event][key](arg));
}

function getIdGenerator() {
    let lastId = 0;

    return function getNextUniqueId() {
        lastId += 1;
        return lastId;
    };
}

module.exports = { publish, subscribe };
