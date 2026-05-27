import { createOrderApi } from './modules/orderApi.js';
import { createPageApi } from './modules/pageApi.js';
import { createProjectApi } from './modules/projectApi.js';

export function createSiteApi(http) {
    return {
        pages: createPageApi(http),
        projects: createProjectApi(http),
        orders: createOrderApi(http),
    };
}
