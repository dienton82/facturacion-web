CREATE TABLE `factura_usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `factura_usuarios`
--

INSERT INTO `factura_usuarios` (`id`, `email`, `password`, `first_name`, `last_name`, `mobile`, `address`) VALUES
(1, 'registro@baulphp.com', '12345', 'BaulPHP', '', 78979676, '');



--
-- Indices de la tabla `factura_usuarios`
--
ALTER TABLE `factura_usuarios`
  ADD PRIMARY KEY (`id`);


--
ALTER TABLE `factura_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
