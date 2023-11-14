<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import {useForm} from '@inertiajs/vue3';
import {router} from '@inertiajs/vue3';
import {Link} from '@inertiajs/vue3';
import {onMounted, reactive, ref} from "vue";

const authUser = usePage().props.auth.user;

const props = defineProps({
    users: {
        type: Array
    },
    chats: {
        type: Array
    }
});

const isGroup = ref(false);
const userIds = ref([]);
const title = ref('');

function submit(id) {
    router.post('/chats', {title: null, users: [id]})
}

const toggleUsers = (id) => {
    let index = userIds.value.indexOf(id)
    if (index === -1) {
        userIds.value.push(id)
    } else {
        userIds.value.splice(index, 1);
    }
}

onMounted(() => {
    Echo.private(`users.${authUser.id}`)
        .listen('.store-message-status', res => {
            console.log(res);
            props.chats.filter(chat => {
                if(chat.id === res.chat_id) {
                    chat.unreadable_count = res.count
                    chat.last_message = res.message
                }
            })

        })
})

const storeGroup = () => {
    router.post('/chats', {title: title.value, users: userIds.value})
}
</script>

<template>
    <Head title="Chat"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex ">
                        <div class="
                        w-1/3
                         p-6
                          text-gray-900
                          bg-slate-200
                        ">
                            <div class="flex justify-center flex-col items-center ">
                                <a
                                    @click.prevent="isGroup = !isGroup"
                                    v-if="!isGroup"
                                    href="#" class="p-2">Make group</a>
                                <div v-if="isGroup" class="flex justify-center flex-col items-center">
                                    <input
                                        v-model="title"
                                        placeholder="title"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    />
                                    <div>
                                        <a
                                            @click.prevent="storeGroup"
                                            href="#"
                                            :class="['p-2', userIds.length < 2 ? 'hover:cursor-not-allowed' : '']">Go
                                            chat</a>
                                        <a
                                            @click.prevent="isGroup = !isGroup"
                                            href="#" class="p-2">x</a>
                                    </div>
                                </div>
                            </div>
                            Users
                            <div class="bg-white mb-3 p-3 shadow-sm rounded-lg" v-for="user in users" :key="user.id">
                                <div class="flex items-center space-x-4">
                                    <div class=" flex items-center flex-1 min-w-0">
                                        <p
                                            @click.prevent="submit(user.id)"
                                            class="font-medium text-gray-900 ">
                                            {{ user.name }}
                                        </p>
                                        <div
                                            v-if="isGroup"
                                            class="ml-2">
                                            <input
                                                v-model="userIds"
                                                v-bind:value="user.id"
                                                type="checkbox">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            Chats
                            <div class="bg-white mb-3 p-3 shadow-sm rounded-lg"
                                 v-for="chat in chats" :key="chat.id">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1 min-w-0">
                                        <Link :href="route('chats.show', chat.id)">
                                            <div class="relative z-1">
                                                <p
                                                    class="font-medium text-gray-900 ">
                                                    {{ chat.title ?? 'Your Chat' }} {{ chat.id }}
                                                </p>
                                                <div
                                                :class="['p-3', chat.unreadable_count !== 0 ? 'bg-sky-50': '']">
                                                    {{chat.last_message.user.name}}: {{chat.last_message.body}}
                                                </div>
                                                <span
                                                    v-if="chat.unreadable_count"
                                                    class="
                                                            absolute
                                                            right-0
                                                            top-[-5px]
                                                            text-xs
                                                            rounded-full
                                                            p-2 bg-indigo-700
                                                            text-white
                                                            z-2
                                                            ">
                                                    {{ chat.unreadable_count }}
                                                </span>
                                            </div>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 text-gray-900">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
