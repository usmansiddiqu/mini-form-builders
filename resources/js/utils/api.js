export const apiRequest = async (url, method, data = null) => {
    try {
        const response = await fetch(`http://127.0.0.1:8000/api/${url}`, {
            method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: data ? JSON.stringify(data) : null,
        });

        console.log(response)
        if (!response.ok) {
            throw new Error('API request failed');
        }

        return await response.json();
    } catch (error) {
        console.error('API error:', error);
        throw error;
    }
};
