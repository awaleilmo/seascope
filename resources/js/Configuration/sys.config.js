import decodeConfig from "@/Configuration/decode.config.js";
import {usePage} from "@inertiajs/vue3";

class sys{

    userAuth(){
        return decodeConfig.base64toJSON(usePage().props.auth.user)
    }
    getImage = async (dataUrl) =>
    {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.src = dataUrl;
            image.onload = () => {
                resolve(image);
            };
            image.onerror = (el, err) => {
                reject(err.error);
            };
        });
    }

    ImageBase64Compress = async (
        file,
        imageType = 'image/jpeg',
        quality = 0.2
    ) => {
        // Create a temporary image so that we can compute the height of the image.
        const image = await this.getImage(file);
        const oldWidth = image.naturalWidth;
        const oldHeight = image.naturalHeight;

        // Create a temporary canvas to draw the downscaled image on.
        const canvas = document.createElement('canvas');
        canvas.width = oldWidth;
        canvas.height = oldHeight;

        // Draw the downscaled image on the canvas and return the new data URL.
        const ctx = canvas.getContext('2d');
        ctx.drawImage(image, 0, 0, oldWidth, oldHeight);
        return canvas.toDataURL(imageType, quality);
    }
}

export default new sys()
