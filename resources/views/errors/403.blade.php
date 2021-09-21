@extends('errors::minimal')

@section('title', __('Acesso negado'))
@section('code', '403')
@section('message', __('Este recurso não pode ser acessado com as permissões que você possui no sistema. Contate o administrador do seu delivery.'))

