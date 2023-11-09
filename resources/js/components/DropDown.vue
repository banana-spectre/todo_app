<template lang="">
    <div 
    class="absolute right-5 top-7 bg-white rounded-2xl text-center
    rounded-tr-sm py-2 px-3 text-lg shadow-[0px_4px_12px_1px_#C6C6C6] flex flex-col font-semibold">
            <button @click="editAgendaTrigger()" class="border-b-2 border-[#D8D8D8] w-20">Edit</button>
            <button @click="deleteAgenda(todo.id)" class="text-red-500">Delete</button>
    </div>
</template>
<script>
export default {
    props: {
        todo: Object,
        getSubjects: Function,
        editAgendaTrigger: Function,
    },
    methods: {
        deleteAgenda(id) {
            axios.delete(`/api/subjects/${id}`)
            .then(()=>this.$props.getSubjects())
            .catch((error) => {
                console.error(error)
            })
        }
    }
}
</script>
