const ApiResponse = {
    SUCCESS: { code: 200, message: 'Success' },
    CREATED: { code: 201, message: 'Created resource' },
    BAD_REQUEST: { code: 400, message: 'Bad Request' },
    UNAUTHORIZED: { code: 401, message: 'Unauthorized' },
    FORBIDDEN: { code: 403, message: 'Forbidden' },
    NOT_FOUND: { code: 404, message: 'Not Found' },
    CONFLICT: { code: 409, message: 'Conflict' },
    SERVER_ERROR: { code: 500, message: 'Internal Server Error' },
    SERVICE_UNAVAILABLE: { code: 503, message: 'Service unavailable' },
};

export default ApiResponse;
