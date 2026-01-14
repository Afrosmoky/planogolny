let googleMapsPromise = null

export function loadGoogleMaps() {
    if (googleMapsPromise) return googleMapsPromise

    googleMapsPromise = new Promise((resolve, reject) => {
        // jeśli już załadowane
        if (window.google?.maps?.Map) {
            resolve(window.google)
            return
        }

        const script = document.createElement('script')
        script.src =
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyCAuQt5RhQuAWVEUQgGr9GzrzzqnZ4BT2g'
        script.async = true
        script.defer = true

        script.onload = () => {
            if (window.google?.maps?.Map) {
                resolve(window.google)
            } else {
                reject(
                    new Error('Google Maps loaded, but Map is missing')
                )
            }
        }

        script.onerror = () =>
            reject(new Error('Failed to load Google Maps API'))

        document.head.appendChild(script)
    })

    return googleMapsPromise
}
