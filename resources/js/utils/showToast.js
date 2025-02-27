export default {
    setup() {
        const toast = useToast();
        const showToast = () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Form saved successfully!', life: 3000 });
        };
        return { showToast };
    }
};
