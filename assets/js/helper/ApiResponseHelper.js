import ApiResponseConfig from '../const/ApiResponseConfig';

class ApiResponseHelper
{
    isSuccess = responseStatusCode => {
        return responseStatusCode === ApiResponseConfig.SUCCESS.code;
    };
}

export default new ApiResponseHelper();
