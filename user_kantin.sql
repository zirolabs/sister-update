-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 14 Feb 2020 pada 06.27
-- Versi server: 5.7.23
-- Versi PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `sister`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_kantin`
--

CREATE TABLE `user_kantin` (
  `user_kantin_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `user_kantin`
--
ALTER TABLE `user_kantin`
  ADD PRIMARY KEY (`user_kantin_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `user_kantin`
--
ALTER TABLE `user_kantin`
  MODIFY `user_kantin_id` int(11) NOT NULL AUTO_INCREMENT;