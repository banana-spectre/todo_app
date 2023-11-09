<template lang="">
    <div class="flex flex-col justify-center w-full items-center">
        <h1 class="font-roboto_medium text-6xl font-bold pt-8 text-[#5A00E3]">TO-DO LIST</h1>
        <Agenda v-for="todo in todo_list" :todo="todo" :getSubjects="getSubjects" />
        <div class="p-4 w-6/12">
            <button type="button" 
            class="border-4 border-[#CECECE] rounded-2xl 
            p-3 w-full hover:bg-[#5A00E3] hover:text-white text-[#D9C8F3] hover:border-[#5A00E3]" 
            @click="modalHandler">
                <p class="bi bi-plus-lg font-roboto_medium text-3xl font-medium"> 
                    Add Agenda
                </p>
            </button>
        </div>
        <AddAgenda v-if="isModalOpen" :modalHandler="modalHandler" :getSubjects="getSubjects" />
    </div>
</template>
<script>

import Agenda from './components/Agenda.vue';
import AddAgenda from './components/AddAgenda.vue';

export default {
    components: {
        Agenda,
        AddAgenda
    },
    data() {
        return {
            isModalOpen: false,
            todo_list: []
        }
    },
    methods: {
        modalHandler() {
            this.isModalOpen = !this.isModalOpen
            window.scrollTo(0,0)
            this.getSubjects()
        },
        getSubjects() {
            axios.get("/api/subjects")
            .then((response) => {
                this.todo_list = response.data
            })
            .catch((error) => {
                console.error(error)
            })
        }
    },
    created() {
        this.getSubjects()
    },
    watch: {
        isModalOpen() {
            if(this.isModalOpen) {
                document.documentElement.style.overflow = 'hidden'
                return
            }
            document.documentElement.style.overflow = 'auto'
        }
    }
}
</script>
