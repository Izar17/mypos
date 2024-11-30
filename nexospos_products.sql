--
-- PostgreSQL database dump
--

-- Dumped from database version 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1)
-- Dumped by pg_dump version 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: nexopos_products; Type: TABLE; Schema: public; Owner: jojo
--

CREATE TABLE public.nexopos_products (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    tax_type character varying(255),
    tax_group_id integer,
    tax_value real DEFAULT '0'::real NOT NULL,
    product_type character varying(255) DEFAULT 'product'::character varying NOT NULL,
    type character varying(255) DEFAULT 'tangible'::character varying NOT NULL,
    accurate_tracking boolean DEFAULT false NOT NULL,
    auto_cogs boolean DEFAULT true NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    stock_management character varying(255) DEFAULT 'enabled'::character varying NOT NULL,
    barcode character varying(255) NOT NULL,
    barcode_type character varying(255) NOT NULL,
    sku character varying(255) NOT NULL,
    description text,
    thumbnail_id integer,
    category_id integer,
    parent_id integer DEFAULT 0 NOT NULL,
    unit_group integer NOT NULL,
    on_expiration character varying(255) DEFAULT 'prevent_sales'::character varying NOT NULL,
    expires boolean DEFAULT false NOT NULL,
    searchable boolean DEFAULT true NOT NULL,
    author integer NOT NULL,
    uuid character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    skip_cooking boolean DEFAULT false NOT NULL,
    modifiers_group_id integer,
    modifiers_groups character varying(191),
    gastro_item_type character varying(191),
    is_senior_disc boolean DEFAULT false NOT NULL
);


ALTER TABLE public.nexopos_products OWNER TO jojo;

--
-- Name: nexopos_products_id_seq; Type: SEQUENCE; Schema: public; Owner: jojo
--

CREATE SEQUENCE public.nexopos_products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nexopos_products_id_seq OWNER TO jojo;

--
-- Name: nexopos_products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jojo
--

ALTER SEQUENCE public.nexopos_products_id_seq OWNED BY public.nexopos_products.id;


--
-- Name: nexopos_products id; Type: DEFAULT; Schema: public; Owner: jojo
--

ALTER TABLE ONLY public.nexopos_products ALTER COLUMN id SET DEFAULT nextval('public.nexopos_products_id_seq'::regclass);


--
-- Data for Name: nexopos_products; Type: TABLE DATA; Schema: public; Owner: jojo
--

COPY public.nexopos_products (id, name, tax_type, tax_group_id, tax_value, product_type, type, accurate_tracking, auto_cogs, status, stock_management, barcode, barcode_type, sku, description, thumbnail_id, category_id, parent_id, unit_group, on_expiration, expires, searchable, author, uuid, created_at, updated_at, skip_cooking, modifiers_group_id, modifiers_groups, gastro_item_type, is_senior_disc) FROM stdin;
125	PURPLE RAIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B19	code128	KDA-B19	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
126	TOXIC WASTED	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B20	code128	KDA-B20	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
127	SATAN'S HEAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B21	code128	KDA-B21	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
128	FAIRWAYS BOMB	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B22	code128	KDA-B22	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
129	BORACAY BLACK SEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B23	code128	KDA-B23	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
130	BMW	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B24	code128	KDA-B24	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
131	TOKYO ICE TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B25	code128	KDA-B25	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
132	JACK & ROSE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B26	code128	KDA-B26	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
133	98 DEGREES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B27	code128	KDA-B27	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
134	JACKCOKE FLOAT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B28	code128	KDA-B28	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	f
135	TIPSY TEXAN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B29	code128	KDA-B29	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
136	BIKINI MARTINI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B30	code128	KDA-B30	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
137	SEX IN THE JUNGLE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B31	code128	KDA-B31	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
138	GODMOTHER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B32	code128	KDA-B32	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
139	BLUE SEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B33	code128	KDA-B33	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
140	COCONUT SUNSET	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B34	code128	KDA-B34	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
141	LEMON DROP	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B35	code128	KDA-B35	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
142	NEGRONI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B36	code128	KDA-B36	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
143	YELLOW BIRD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B37	code128	KDA-B37	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
144	HAIRY FUZZY	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B38	code128	KDA-B38	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
145	AUGUSTA PARADISE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B39	code128	KDA-B39	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:14	2024-07-16 19:57:14	f	0		product	f
146	TEQUILA SUNRISE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B40	code128	KDA-B40	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
147	AMARRETO SUNSET	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B41	code128	KDA-B41	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
148	SANGRIA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B42	code128	KDA-B42	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
149	BLOW JOB	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B43	code128	KDA-B43	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
150	B52	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B44	code128	KDA-B44	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
151	SEX ON THE BEACH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B45	code128	KDA-B45	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
152	CLASSIC MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B46	code128	KDA-B46	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
153	BLUE MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B47	code128	KDA-B47	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
154	MANGO MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B48	code128	KDA-B48	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
155	BANANA MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B49	code128	KDA-B49	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
156	WATERMELON MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B50	code128	KDA-B50	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:15	2024-07-16 19:57:15	f	0		product	f
157	PINEAPPLE MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B51	code128	KDA-B51	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
158	LYCHEE MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B52	code128	KDA-B52	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
159	CLASSIC DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B53	code128	KDA-B53	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
160	MANGO DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B54	code128	KDA-B54	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
161	BANANA DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B55	code128	KDA-B55	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
162	WATERMELON DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B56	code128	KDA-B56	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
163	PINEAPPLE DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B57	code128	KDA-B57	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
164	LYCHEE DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B58	code128	KDA-B58	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
166	MANGO MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B60	code128	KDA-B60	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
167	WATERMELON MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B61	code128	KDA-B61	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
168	PINEAPPLE MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B62	code128	KDA-B62	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:16	2024-07-16 19:57:16	f	0		product	f
169	LYCHEE MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B63	code128	KDA-B63	\N	\N	6	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	f
170	MAI THAI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B64	code128	KDA-B64	\N	\N	6	0	4	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	f
171	RUM COKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B65	code128	KDA-B65	\N	\N	6	0	4	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	f
172	TEQUILA SUNRISE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B66	code128	KDA-B66	\N	\N	6	0	4	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	f
252	EMPERADOR LIGHT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B95	code128	KDA-B95	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	f
253	EMPERADOR LIGHT 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B96	code128	KDA-B96	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	f
254	FUNDADOR LIGHT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B97	code128	KDA-B97	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	f
255	FUNDADOR GOLD 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B98	code128	KDA-B98	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	f
256	EMPERADOR GOLD 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B99	code128	KDA-B99	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
257	ANDY PLAYER 500ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B100	code128	KDA-B100	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
258	JURA ORIGIN 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B101	code128	KDA-B101	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
259	THE DALMORE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B102	code128	KDA-B102	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
260	EMPERADOR LIGHT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B103	code128	KDA-B103	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
261	EMPERADOR LIGHT 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B104	code128	KDA-B104	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
262	FUNDADOR LIGHT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B105	code128	KDA-B105	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
265	ANDY PLAYER 500ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B108	code128	KDA-B108	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:25	2024-07-16 19:57:25	f	0		product	f
266	JURA ORIGIN 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B109	code128	KDA-B109	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
267	THE DALMORE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B110	code128	KDA-B110	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
268	CHIVAS REGAL 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B111	code128	KDA-B111	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
269	JAMESON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B112	code128	KDA-B112	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
270	JAMESON 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B113	code128	KDA-B113	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
271	JIMBEAM BOURBON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B114	code128	KDA-B114	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
272	JIMBEAM BALCK 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B115	code128	KDA-B115	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:26	2024-07-16 19:57:26	f	0		product	f
173	SEX ON THE BEACH WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B67	code128	KDA-B67	\N	\N	6	0	4	prevent-sales	f	t	12	\N	2024-07-16 19:57:17	2024-10-20 18:34:08	f	0		product	f
174	LONG ISLAND ICED TEA WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B68	code128	KDA-B68	\N	\N	6	0	4	prevent-sales	f	t	12	\N	2024-07-16 19:57:17	2024-10-20 18:36:32	f	0		product	f
264	EMPERADOR GOLD 1L SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B107	code128	KDA-B107	\N	\N	12	0	6	prevent-sales	f	t	12	\N	2024-07-16 19:57:25	2024-10-21 17:37:28	f	0		product	f
263	FUNDADOR GOLD 750ML SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B106	code128	KDA-B106	\N	\N	12	0	6	prevent-sales	f	t	12	\N	2024-07-16 19:57:25	2024-10-21 17:37:47	f	0		product	f
277	JACK DANIELS 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B120	code128	KDA-B120	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:27	2024-07-16 19:57:27	f	0		product	f
278	JACK DANIELS 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B121	code128	KDA-B121	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:27	2024-07-16 19:57:27	f	0		product	f
283	JIMBEAM BALCK 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B126	code128	KDA-B126	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:27	2024-07-16 19:57:27	f	0		product	f
288	JACK DANIELS 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B131	code128	KDA-B131	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
289	JACK DANIELS 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B132	code128	KDA-B132	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
290	BOMBAY GIN 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B133	code128	KDA-B133	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
291	TANQUERAY 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B134	code128	KDA-B134	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
292	LOCAL GIN 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B135	code128	KDA-B135	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
293	BOMBAY GIN 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B136	code128	KDA-B136	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
294	TANQUERAY 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B137	code128	KDA-B137	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
295	LOCAL GIN 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B138	code128	KDA-B138	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
296	ABSOLUT VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B139	code128	KDA-B139	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
297	ABSOLUT VODKA 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B140	code128	KDA-B140	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:28	2024-07-16 19:57:28	f	0		product	f
298	ABSOLUT CITRON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B141	code128	KDA-B141	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
299	ABSOLUT KURANT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B142	code128	KDA-B142	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
300	GREYGOOSE VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B143	code128	KDA-B143	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
301	BELVEDERE VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B144	code128	KDA-B144	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
302	ABSOLUT VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B145	code128	KDA-B145	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
303	ABSOLUT VODKA 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B146	code128	KDA-B146	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
304	ABSOLUT CITRON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B147	code128	KDA-B147	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
305	ABSOLUT KURANT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B148	code128	KDA-B148	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
306	GREYGOOSE VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B149	code128	KDA-B149	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
307	BELVEDERE VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B150	code128	KDA-B150	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:29	2024-07-16 19:57:29	f	0		product	f
281	JAMESON 1L SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B124	code128	KDA-B124	\N	\N	12	0	6	prevent-sales	f	t	12	\N	2024-07-16 19:57:27	2024-10-21 17:43:41	f	0		product	f
280	JAMESON 700ML SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B123	code128	KDA-B123	\N	\N	12	0	6	prevent-sales	f	t	12	\N	2024-07-16 19:57:27	2024-10-21 17:44:18	f	0		product	f
282	JIMBEAM BOURBON 700ML SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B125	code128	KDA-B125	\N	\N	12	0	6	prevent-sales	f	t	12	\N	2024-07-16 19:57:27	2024-10-21 17:46:25	f	0		product	f
311	PATRON ANEJO 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B154	code128	KDA-B154	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:30	2024-07-16 19:57:30	f	0		product	f
312	PATRON ANEJO SILVER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B155	code128	KDA-B155	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:30	2024-07-16 19:57:30	f	0		product	f
313	LOCAL TEQUILA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B156	code128	KDA-B156	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:30	2024-07-16 19:57:30	f	0		product	f
317	PATRON ANEJO 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B160	code128	KDA-B160	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:30	2024-07-16 19:57:30	f	0		product	f
318	PATRON ANEJO SILVER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B161	code128	KDA-B161	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
319	LOCAL TEQUILA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B162	code128	KDA-B162	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
320	BACARDI BLACK 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B163	code128	KDA-B163	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
321	BACARDI GOLD 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B164	code128	KDA-B164	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
322	BACARDI SUPERIOR 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B165	code128	KDA-B165	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
323	CAPTAIN MORGAN 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B166	code128	KDA-B166	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
324	LOCAL RUM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B167	code128	KDA-B167	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
325	BACARDI BLACK 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B168	code128	KDA-B168	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
326	BACARDI GOLD 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B169	code128	KDA-B169	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
327	BACARDI SUPERIOR 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B170	code128	KDA-B170	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:31	2024-07-16 19:57:31	f	0		product	f
328	CAPTAIN MORGAN 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B171	code128	KDA-B171	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
329	LOCAL RUM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B172	code128	KDA-B172	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
330	BAILEYS 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B173	code128	KDA-B173	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
331	CAMPARI 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B174	code128	KDA-B174	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
332	JAGERMAEISTER 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B175	code128	KDA-B175	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
333	KAHLUA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B176	code128	KDA-B176	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
334	MARTINI EXTRA DRY1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B177	code128	KDA-B177	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
335	TEQUILA ROSE 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B178	code128	KDA-B178	\N	\N	12	0	5	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
336	BAILEYS 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B179	code128	KDA-B179	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
337	CAMPARI 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B180	code128	KDA-B180	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:32	2024-07-16 19:57:32	f	0		product	f
338	JAGERMAEISTER 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B181	code128	KDA-B181	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	f
339	KAHLUA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B182	code128	KDA-B182	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	f
340	MARTINI EXTRA DRY1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B183	code128	KDA-B183	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	f
341	TEQUILA ROSE 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B184	code128	KDA-B184	\N	\N	12	0	6	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	f
360	NATURE KISS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B01	code128	KDA-B01	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	f
361	AUGUSTA SUNSET	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B02	code128	KDA-B02	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	f
362	STRAWBERRY MINT ICED TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B03	code128	KDA-B03	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	f
363	GREEN GODDESS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B04	code128	KDA-B04	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
364	CUCUMBER LEMONADE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B05	code128	KDA-B05	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
365	DISNEY PRINCESS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B06	code128	KDA-B06	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
366	MOCKJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B07	code128	KDA-B07	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
367	RED FLASH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B08	code128	KDA-B08	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
368	CITRUS PUNCH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B09	code128	KDA-B09	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
369	COCONUT UBE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B10	code128	KDA-B10	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:36	2024-07-16 19:57:36	f	0		product	f
370	BLACK MONKEY	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B11	code128	KDA-B11	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
371	COOKIES & CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B12	code128	KDA-B12	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
372	COCONUT GRAHAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B13	code128	KDA-B13	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
373	TROPICAL BLAST	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B14	code128	KDA-B14	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
374	MINTY CUCUMBER LYCHEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B15	code128	KDA-B15	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
375	MINTY LEMONADE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B16	code128	KDA-B16	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
376	TE AMO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B17	code128	KDA-B17	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
377	MARGARITA MOCKTAIL	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B18	code128	KDA-B18	\N	\N	15	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	f
1	TOFU BAY LEAF PORK	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F55	code128	KDA-F55	\N	\N	1	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:21:17	2024-07-16 19:21:17	f	0		product	t
99	MANGO JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B69	code128	KDA-B69	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
100	BANANA JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B70	code128	KDA-B70	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
101	PINEAPPLE JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B71	code128	KDA-B71	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
102	ORANGE JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B72	code128	KDA-B72	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
103	APPLE JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B73	code128	KDA-B73	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
104	CALAMANSI JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B74	code128	KDA-B74	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
105	COCONUT JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B75	code128	KDA-B75	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
106	WATERMELON JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B76	code128	KDA-B76	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
107	ICED TEA JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B77	code128	KDA-B77	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
108	LEMONADE JUICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B78	code128	KDA-B78	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
109	MANGO SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B79	code128	KDA-B79	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
110	BANANA SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B80	code128	KDA-B80	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
111	PINEAPPLE SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B81	code128	KDA-B81	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:11	2024-07-16 19:57:11	f	0		product	t
112	ORANGE SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B82	code128	KDA-B82	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
113	APPLE SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B83	code128	KDA-B83	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
114	CALAMANSI SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B84	code128	KDA-B84	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
115	COCONUT SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B85	code128	KDA-B85	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
116	WATERMELON SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B86	code128	KDA-B86	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
117	FROZEN ICED TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B87	code128	KDA-B87	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
118	LEMONADE SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B88	code128	KDA-B88	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
119	LYCHEE SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B89	code128	KDA-B89	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
120	CHOCOLATE SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B90	code128	KDA-B90	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
121	BANANA MANGO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B91	code128	KDA-B91	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
122	MANGO LYCHEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B92	code128	KDA-B92	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:12	2024-07-16 19:57:12	f	0		product	t
123	PINEAPPLE ORANGE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B93	code128	KDA-B93	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	t
124	ORANGE MANGO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B94	code128	KDA-B94	\N	\N	5	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:13	2024-07-16 19:57:13	f	0		product	t
175	ESPRESSO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K1	code128	KDA-K1	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	t
176	DOUBLE ESPRESSO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K2	code128	KDA-K2	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	t
177	AMERICANO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K3	code128	KDA-K3	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	t
178	CAFE LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K4	code128	KDA-K4	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	t
179	CAFE MOCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K5	code128	KDA-K5	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:17	2024-07-16 19:57:17	f	0		product	t
180	CAPPUCINO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K6	code128	KDA-K6	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
181	HOT CHOCOLATE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K7	code128	KDA-K7	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
182	DIRTY CHAI LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K8	code128	KDA-K8	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
183	DIRTY MATCHA LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K9	code128	KDA-K9	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
184	CARAMEL MACHIATO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K10	code128	KDA-K10	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
185	AMERICANO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K13	code128	KDA-K13	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
186	CAFE LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K14	code128	KDA-K14	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
187	CAFE MOCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K15	code128	KDA-K15	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
188	CAPPUCINO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K16	code128	KDA-K16	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
189	HOT CHOCOLATE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K17	code128	KDA-K17	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
190	DIRTY CHAI LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K18	code128	KDA-K18	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:18	2024-07-16 19:57:18	f	0		product	t
191	DIRTY MATCHA LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K19	code128	KDA-K19	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
192	CARAMEL MACHIATO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K20	code128	KDA-K20	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
193	THAI BROWN COFFEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K21	code128	KDA-K21	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
194	THAI TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K22	code128	KDA-K22	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
195	THAI TEA COFFEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K23	code128	KDA-K23	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
196	TWO TONE TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K24	code128	KDA-K24	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
197	ICED DIRTY CHAI LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K25	code128	KDA-K25	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
198	ICED DIRTY MATCHA LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K26	code128	KDA-K26	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
199	ICED AMERICANO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K27	code128	KDA-K27	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
200	AMERICANO HONEY ROSE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K28	code128	KDA-K28	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
201	AMERICANO HONEY LEMON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K29	code128	KDA-K29	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
202	AMERICANO ORANGE CHIA SEEDS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K30	code128	KDA-K30	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:19	2024-07-16 19:57:19	f	0		product	t
203	ICED LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K31	code128	KDA-K31	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
204	ICED MOCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K32	code128	KDA-K32	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
205	ICED CAPPUCINO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K33	code128	KDA-K33	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
206	ICED SPANISH LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K34	code128	KDA-K34	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
207	ICED CARAMEL MACHIATO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K35	code128	KDA-K35	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
208	THAI BROWN COFFEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K36	code128	KDA-K36	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
209	THAI TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K37	code128	KDA-K37	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
210	THAI TEA COFFEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K38	code128	KDA-K38	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
211	TWO TONE TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K39	code128	KDA-K39	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
212	ICED DIRTY CHAI LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K40	code128	KDA-K40	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:20	2024-07-16 19:57:20	f	0		product	t
213	ICED DIRTY MATCHA LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K41	code128	KDA-K41	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
214	ICED AMERICANO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K42	code128	KDA-K42	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
215	AMERICANO HONEY ROSE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K43	code128	KDA-K43	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
216	AMERICANO HONEY LEMON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K44	code128	KDA-K44	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
217	AMERICANO ORANGE CHIA SEEDS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K45	code128	KDA-K45	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
218	ICED LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K46	code128	KDA-K46	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
219	ICED MOCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K47	code128	KDA-K47	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
220	ICED CAPPUCINO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K48	code128	KDA-K48	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
221	ICED SPANISH LATTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K49	code128	KDA-K49	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
222	ICED CARAMEL MACHIATO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K50	code128	KDA-K50	\N	\N	7	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:21	2024-07-16 19:57:21	f	0		product	t
223	BANANA FRITTERS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F29	code128	KDA-F29	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
224	SPECIAL TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F30	code128	KDA-F30	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
225	BANANA CON QUEZO TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F31	code128	KDA-F31	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
226	PLATANO DE CARAMELLO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F32	code128	KDA-F32	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
227	COCO SEMIFREDO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F33	code128	KDA-F33	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
228	BROWNIE A LA MODE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F34	code128	KDA-F34	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
229	MANGO QUEZO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F35	code128	KDA-F35	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
230	VANILLA ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F36	code128	KDA-F36	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
231	UBE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F37	code128	KDA-F37	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
232	MANGO ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F38	code128	KDA-F38	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
233	CHOCOLATE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F39	code128	KDA-F39	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:22	2024-07-16 19:57:22	f	0		product	t
234	SEASONAL FRUIT PLATTER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F40	code128	KDA-F40	\N	\N	8	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
235	LASWA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F60	code128	KDA-F60	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
236	KAPAMPANGANS CHOP SUEY	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F61	code128	KDA-F61	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
237	BAGNET PINAKBET	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F62	code128	KDA-F62	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
238	ADOBO KANGKONG WITH TOFU	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F63	code128	KDA-F63	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
239	BUTTERED MIXED VEGETABLES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F64	code128	KDA-F64	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
240	GUISADONG TALONG WITH GROUND PORK	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F65	code128	KDA-F65	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
241	LAING TILAPIA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F66	code128	KDA-F66	\N	\N	9	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
242	CHAO FAN BORACAY FRIED RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F22	code128	KDA-F22	\N	\N	10	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:23	2024-07-16 19:57:23	f	0		product	t
245	VEGETABLE FRIED RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F25	code128	KDA-F25	\N	\N	10	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
246	STEAMED RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F26	code128	KDA-F26	\N	\N	10	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
247	GARLIC PEPPERY CRAB	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F01	code128	KDA-F01	\N	\N	11	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
248	PHOO PHAD PONG KAREE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F02	code128	KDA-F02	\N	\N	11	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
249	COCONUT SEAFOOD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F03	code128	KDA-F03	\N	\N	11	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
250	CHILI PEPPER SQUID	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F04	code128	KDA-F04	\N	\N	11	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
251	SINIGANG PRAWNS SA MANGGA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F05	code128	KDA-F05	\N	\N	11	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:24	2024-07-16 19:57:24	f	0		product	t
342	FAIRWAYS FRIED CHICKEN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F49	code128	KDA-F49	\N	\N	13	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	t
343	GRILLED TURMERIC CHICKEN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F50	code128	KDA-F50	\N	\N	13	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	t
344	DIRTY MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K56	code128	KDA-K56	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	t
345	MATCHA MIZU	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K57	code128	KDA-K57	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	t
346	CHAI MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K58	code128	KDA-K58	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	t
347	PEACH MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K59	code128	KDA-K59	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:33	2024-07-16 19:57:33	f	0		product	t
348	CHOCOLATE MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K60	code128	KDA-K60	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
349	BANANA CHOCO MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K61	code128	KDA-K61	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
350	STRAWBERRY MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K62	code128	KDA-K62	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
351	MANGO CARAMEL MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K63	code128	KDA-K63	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
352	DIRTY MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K64	code128	KDA-K64	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
353	MATCHA MIZU	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K65	code128	KDA-K65	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
354	CHAI MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K66	code128	KDA-K66	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
355	PEACH MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K67	code128	KDA-K67	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:34	2024-07-16 19:57:34	f	0		product	t
356	CHOCOLATE MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K68	code128	KDA-K68	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	t
357	BANANA CHOCO MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K69	code128	KDA-K69	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	t
358	STRAWBERRY MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K70	code128	KDA-K70	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	t
359	MANGO CARAMEL MATCHA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K71	code128	KDA-K71	\N	\N	14	0	1	prevent-sales	f	t	6	\N	2024-07-16 19:57:35	2024-07-16 19:57:35	f	0		product	t
378	PORK BELLY SHRIMP SALTED EGG SALAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F45	code128	KDA-F45	\N	\N	16	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:37	2024-07-16 19:57:37	f	0		product	t
379	ENSALADANG LABANOS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F46	code128	KDA-F46	\N	\N	16	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
380	AHI POKE SALAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F47	code128	KDA-F47	\N	\N	16	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
381	CAESAR SALAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F48	code128	KDA-F48	\N	\N	16	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
382	BINONDO CRISPY FRIED NOODLES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F08	code128	KDA-F08	\N	\N	17	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
383	PANCIT HAB-HAB	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F09	code128	KDA-F09	\N	\N	17	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
384	CHAO MIFEN BIHON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F10	code128	KDA-F10	\N	\N	17	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
385	CHINOY PANCIT WITH TENDER BEEF	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F11	code128	KDA-F11	\N	\N	17	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
386	ALMOND AND DARK CHOCO MIX COOKIES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F73	code128	KDA-F73	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:38	2024-07-16 19:57:38	f	0		product	t
244	SINANGAG NA KANIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F24	code128	KDA-F24	\N	\N	10	0	2	prevent-sales	f	t	11	\N	2024-07-16 19:57:23	2024-10-17 12:34:42	f	0		product	t
387	CHOC OATS COOKIES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F74	code128	KDA-F74	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
388	LIME AND SWEET CHILI COOKIES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F75	code128	KDA-F75	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
389	COOKIES WITH WALNUTS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F76	code128	KDA-F76	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
390	BROWNIE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F77	code128	KDA-F77	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
391	CALAMANSI MUFFIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F78	code128	KDA-F78	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
392	CALAMANSI TART	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F79	code128	KDA-F79	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
393	MATCHA CHEESECAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F80	code128	KDA-F80	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
394	MANGO CHEESECAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F81	code128	KDA-F81	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
395	BLUEBERRY CHEESECAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F82	code128	KDA-F82	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:39	2024-07-16 19:57:39	f	0		product	t
396	CARAMEL CHEESECAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F83	code128	KDA-F83	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
397	TRIPLE CHOCOLATE MOUSSE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F84	code128	KDA-F84	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
398	RED VELVET	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F85	code128	KDA-F85	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
399	PISTACHIO CHEESECAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F86	code128	KDA-F86	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
400	TIRAMISU	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F87	code128	KDA-F87	\N	\N	18	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
401	CHAIRMANS CHOICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F12	code128	KDA-F12	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
402	LA MARGHERITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F13	code128	KDA-F13	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
403	KUNG PAO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F14	code128	KDA-F14	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
404	GEISHA SURFER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F15	code128	KDA-F15	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:40	2024-07-16 19:57:40	f	0		product	t
405	PEPPERONI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F16	code128	KDA-F16	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
406	VEGETARIAN PIZZA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F17	code128	KDA-F17	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
407	HAM & CHEESE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F18	code128	KDA-F18	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
408	AUGUSTA PIZZA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F19	code128	KDA-F19	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
409	MARINERO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F20	code128	KDA-F20	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
410	CHICKEN ADOBO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F21	code128	KDA-F21	\N	\N	19	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
411	FRIED MORTADELLA SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F88	code128	KDA-F88	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
412	PROSCIUTTO MUSHROOM SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F89	code128	KDA-F89	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:41	2024-07-16 19:57:41	f	0		product	t
413	FARMERS' HAM SANDWICH WITH BACON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F90	code128	KDA-F90	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
414	KIDS FRANKS SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F91	code128	KDA-F91	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
415	HUNGARIAN SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F92	code128	KDA-F92	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
416	FRANKFURTER SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F93	code128	KDA-F93	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
417	MANGO & PROSCIUTTO SALAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F94	code128	KDA-F94	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
418	WATERCRESS SALAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F95	code128	KDA-F95	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
419	SUNNY TUNA SALPICAO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F96	code128	KDA-F96	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
420	PAD GRAPOW GAI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F97	code128	KDA-F97	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
421	PAD GRAPOW MOO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F98	code128	KDA-F98	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:42	2024-07-16 19:57:42	f	0		product	t
422	PREMIUM FRUIT PLATTER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F99	code128	KDA-F99	\N	\N	20	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
423	CHAIRMANS BURGER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F27	code128	KDA-F27	\N	\N	21	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
424	CLUB SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F28	code128	KDA-F28	\N	\N	21	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
425	FRENCH FRIES	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F06	code128	KDA-F06	\N	\N	22	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
426	KAMOTE CHIPS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F07	code128	KDA-F07	\N	\N	22	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
427	GUISADONG MUNG BEANS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F41	code128	KDA-F41	\N	\N	23	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
428	COCONUT AND PUMPKIN SOUP	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F42	code128	KDA-F42	\N	\N	23	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
429	SWEET CORN AND CHICKEN SOUP	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F43	code128	KDA-F43	\N	\N	23	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
430	HINALANG	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F44	code128	KDA-F44	\N	\N	23	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:43	2024-07-16 19:57:43	f	0		product	t
431	ORTAGGIO PESTO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F67	code128	KDA-F67	\N	\N	24	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
432	SOVRACCARICO DI PROSCIUTTO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F68	code128	KDA-F68	\N	\N	24	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
433	BESCIAMELLA SALMONE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F69	code128	KDA-F69	\N	\N	24	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
434	PROSCIUTTO FUNGHI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F70	code128	KDA-F70	\N	\N	24	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
435	MOREADELLA AL TARTUFO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F71	code128	KDA-F71	\N	\N	24	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
436	QUATTRO FORMAGGI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F72	code128	KDA-F72	\N	\N	24	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
437	TURMERIC TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K51	code128	KDA-K51	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
438	LEMON GINGER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K52	code128	KDA-K52	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:44	2024-07-16 19:57:44	f	0		product	t
439	HOT CALAMANSI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K53	code128	KDA-K53	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
440	MINTY GREEN TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K54	code128	KDA-K54	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
441	CHAI TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K55	code128	KDA-K55	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
442	PURE GREEN TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K72	code128	KDA-K72	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
91	PAKSIW NA SALMON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F56	code128	KDA-F56	\N	\N	1	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
92	CRISPY TIGER PRAWNS TEMPURA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F57	code128	KDA-F57	\N	\N	1	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
93	BEEF SALPICAO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F58	code128	KDA-F58	\N	\N	1	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
94	LAING PRAWNS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F59	code128	KDA-F59	\N	\N	1	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
95	LECHON KAWALI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F51	code128	KDA-F51	\N	\N	3	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
96	RED CURRY PORK BAGNET	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F52	code128	KDA-F52	\N	\N	3	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
97	BISTEK TAGALOG	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F53	code128	KDA-F53	\N	\N	4	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
98	BEEF KARE-KARE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F54	code128	KDA-F54	\N	\N	4	0	2	prevent-sales	f	t	6	\N	2024-07-16 19:57:10	2024-07-16 19:57:10	f	0		product	t
443	EARL GREY TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K73	code128	KDA-K73	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
444	ENGLISH BREAKFAST TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K74	code128	KDA-K74	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
445	PURE CAMOMILE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K75	code128	KDA-K75	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
446	PURE PEPPERMINT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K76	code128	KDA-K76	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
447	GREEN TEA CHAI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K77	code128	KDA-K77	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:45	2024-07-16 19:57:45	f	0		product	t
448	GREEN TEA JASMIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K78	code128	KDA-K78	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:46	2024-07-16 19:57:46	f	0		product	t
449	GREEN TEA MINT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-K79	code128	KDA-K79	\N	\N	25	0	3	prevent-sales	f	t	6	\N	2024-07-16 19:57:46	2024-07-16 19:57:46	f	0		product	t
629	BANANA MANGO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 227	code128	VKWC 227	\N	\N	29	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:44	2024-08-03 00:17:44	f	0		product	t
630	MANGO LYCHEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 228	code128	VKWC 228	\N	\N	29	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:44	2024-08-03 00:17:44	f	0		product	t
631	ORANGE MANGO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 230	code128	VKWC 230	\N	\N	29	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:44	2024-08-03 00:17:44	f	0		product	t
632	PINEAPPLE ORANGE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 229	code128	VKWC 229	\N	\N	29	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:44	2024-08-03 00:17:44	f	0		product	t
659	HOT CALAMANSI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 236	code128	VKWC 236	\N	\N	32	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:51	2024-08-03 00:17:51	f	0		product	t
660	HOT CHOCOLATE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 237	code128	VKWC 237	\N	\N	32	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:51	2024-08-03 00:17:51	f	0		product	t
666	APPLE  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 209	code128	VKWC 209	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:53	2024-08-03 00:17:53	f	0		product	t
667	BANANA  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 206	code128	VKWC 206	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:53	2024-08-03 00:17:53	f	0		product	t
668	CALAMANSI  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 210	code128	VKWC 210	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:53	2024-08-03 00:17:53	f	0		product	t
669	COCONUT  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 211	code128	VKWC 211	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:53	2024-08-03 00:17:53	f	0		product	t
670	ICED TEA 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 213	code128	VKWC 213	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:54	2024-08-03 00:17:54	f	0		product	t
671	LEMONADE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 214	code128	VKWC 214	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:54	2024-08-03 00:17:54	f	0		product	t
672	MANGO JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 205	code128	VKWC 205	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:54	2024-08-03 00:17:54	f	0		product	t
673	ORANGE  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 208	code128	VKWC 208	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:54	2024-08-03 00:17:54	f	0		product	t
674	PINEAPPLE  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 207	code128	VKWC 207	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:54	2024-08-03 00:17:54	f	0		product	t
675	WATERMELON  JUICE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 212	code128	VKWC 212	\N	\N	34	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:55	2024-08-03 00:17:55	f	0		product	t
720	APPLE  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 219	code128	VKWC 219	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:05	2024-08-03 00:18:05	f	0		product	t
721	BANANA  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 216	code128	VKWC 216	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:05	2024-08-03 00:18:05	f	0		product	t
722	CALAMANSI  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 220	code128	VKWC 220	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:05	2024-08-03 00:18:05	f	0		product	t
723	CHOCOLATE  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 226	code128	VKWC 226	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:06	2024-08-03 00:18:06	f	0		product	t
724	COCONUT  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 221	code128	VKWC 221	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:06	2024-08-03 00:18:06	f	0		product	t
725	FROZEN ICED TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 223	code128	VKWC 223	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:06	2024-08-03 00:18:06	f	0		product	t
726	LEMONADE  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 224	code128	VKWC 224	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:06	2024-08-03 00:18:06	f	0		product	t
727	LYCHEE  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 225	code128	VKWC 225	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:07	2024-08-03 00:18:07	f	0		product	t
728	MANGO  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 215	code128	VKWC 215	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:07	2024-08-03 00:18:07	f	0		product	t
729	ORANGE  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 218	code128	VKWC 218	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:07	2024-08-03 00:18:07	f	0		product	t
730	PINEAPPLE  SHAKE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 217	code128	VKWC 217	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:08	2024-08-03 00:18:08	f	0		product	t
657	ESPRESSO WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 231	code128	VKWC 231	\N	\N	32	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:50	2024-10-20 18:37:11	f	0		product	t
658	HOT AMERICANO WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 232	code128	VKWC 232	\N	\N	32	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:50	2024-10-20 18:38:20	f	0		product	t
662	ICED CAPPUCCINO WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 241	code128	VKWC 241	\N	\N	33	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:51	2024-10-21 13:05:22	f	0		product	t
656	CAPPUCCINO WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 235	code128	VKWC 235	\N	\N	32	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:50	2024-10-21 13:05:50	f	0		product	t
665	SPANISH LATTE WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 242	code128	VKWC 242	\N	\N	33	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:52	2024-10-21 13:10:04	f	0		product	t
654	CAFE LATTE WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 233	code128	VKWC 233	\N	\N	32	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:49	2024-10-21 13:13:37	f	0		product	t
663	ICED LATTE WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 239	code128	VKWC 239	\N	\N	33	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:51	2024-10-21 13:14:14	f	0		product	t
664	ICED MOCHA WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 240	code128	VKWC 240	\N	\N	33	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:52	2024-10-21 13:15:45	f	0		product	t
655	CAFE MOCHA WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 234	code128	VKWC 234	\N	\N	32	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:17:50	2024-10-21 13:16:06	f	0		product	t
731	WATERMELON  SHAKE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 222	code128	VKWC 222	\N	\N	39	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:18:08	2024-08-03 00:18:08	f	0		product	t
746	EARL GREY	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 249	code128	VKWC 249	\N	\N	42	0	7	prevent-sales	f	t	6	\N	2024-08-03 00:18:11	2024-08-03 00:18:11	f	0		product	t
747	ENGLISH BREAKFAST	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 247	code128	VKWC 247	\N	\N	42	0	7	prevent-sales	f	t	6	\N	2024-08-03 00:18:11	2024-08-03 00:18:11	f	0		product	t
748	GREEN TEA & MINT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 245	code128	VKWC 245	\N	\N	42	0	7	prevent-sales	f	t	6	\N	2024-08-03 00:18:12	2024-08-03 00:18:12	f	0		product	t
749	GREEN TEA JASMINE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 244	code128	VKWC 244	\N	\N	42	0	7	prevent-sales	f	t	6	\N	2024-08-03 00:18:12	2024-08-03 00:18:12	f	0		product	t
751	PURE CAMOMILE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 246	code128	VKWC 246	\N	\N	42	0	7	prevent-sales	f	t	6	\N	2024-08-03 00:18:12	2024-08-03 00:18:12	f	0		product	t
752	PURE GREEN TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 248	code128	VKWC 248	\N	\N	42	0	7	prevent-sales	f	t	6	\N	2024-08-03 00:18:12	2024-08-03 00:18:12	f	0		product	t
766	COKE REGULAR 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 110	code128	VKWC 110	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:15	2024-08-03 00:18:15	f	0		product	t
767	COKE ZERO 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 111	code128	VKWC 111	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:15	2024-08-03 00:18:15	f	0		product	t
768	PURIFIED WATER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 117	code128	VKWC 117	\N	\N	44	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:15	2024-08-03 00:18:15	f	0		product	t
769	RED BULL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 114	code128	VKWC 114	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:16	2024-08-03 00:18:16	f	0		product	t
770	ROYAL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 113	code128	VKWC 113	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:16	2024-08-03 00:18:16	f	0		product	t
771	SODA WATER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 115	code128	VKWC 115	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:16	2024-08-03 00:18:16	f	0		product	t
772	SPRAKLING WATER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 118	code128	VKWC 118	\N	\N	44	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:16	2024-08-03 00:18:16	f	0		product	t
773	SPRITE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 112	code128	VKWC 112	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:17	2024-08-03 00:18:17	f	0		product	t
774	TONIC WATER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 116	code128	VKWC 116	\N	\N	44	0	9	prevent-sales	f	t	6	\N	2024-08-03 00:18:17	2024-08-03 00:18:17	f	0		product	t
567	AMARETTO SUNSET	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 179	code128	VKWC 179	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:28	2024-08-03 00:17:28	f	0		product	f
568	ANGRY BALLS COCKTAIL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 163	code128	VKWC 163	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:28	2024-08-03 00:17:28	f	0		product	f
569	APPLE COCKTINI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 156	code128	VKWC 156	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:28	2024-08-03 00:17:28	f	0		product	f
570	B52	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 189	code128	VKWC 189	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:29	2024-08-03 00:17:29	f	0		product	f
571	BANANA DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 196	code128	VKWC 196	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:29	2024-08-03 00:17:29	f	0		product	f
572	BANANA MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 173	code128	VKWC 173	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:29	2024-08-03 00:17:29	f	0		product	f
573	BLACK RUSSIAN	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 182	code128	VKWC 182	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:29	2024-08-03 00:17:29	f	0		product	f
574	BLOODY MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 138	code128	VKWC 138	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:30	2024-08-03 00:17:30	f	0		product	f
575	BLOW JOB	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 190	code128	VKWC 190	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:30	2024-08-03 00:17:30	f	0		product	f
576	BLUE KAMIKAZE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 178	code128	VKWC 178	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:30	2024-08-03 00:17:30	f	0		product	f
577	BLUE MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 171	code128	VKWC 171	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:30	2024-08-03 00:17:30	f	0		product	f
578	BORACAY MARTINI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 162	code128	VKWC 162	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:30	2024-08-03 00:17:30	f	0		product	f
579	CITRUS BLAST	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 169	code128	VKWC 169	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:31	2024-08-03 00:17:31	f	0		product	f
580	CLASSIC DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 194	code128	VKWC 194	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:31	2024-08-03 00:17:31	f	0		product	f
581	CLASSIC MARGARITA 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 170	code128	VKWC 170	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:31	2024-08-03 00:17:31	f	0		product	f
582	CLASSIC MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 184	code128	VKWC 184	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:31	2024-08-03 00:17:31	f	0		product	f
583	COCONUT SUNRISE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 151	code128	VKWC 151	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:32	2024-08-03 00:17:32	f	0		product	f
584	FAIRWAYS COCKTAIL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 157	code128	VKWC 157	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:32	2024-08-03 00:17:32	f	0		product	f
585	FAIRWAYS MALIBU	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 159	code128	VKWC 159	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:32	2024-08-03 00:17:32	f	0		product	f
586	FAIRWAYS MULE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 139	code128	VKWC 139	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:32	2024-08-03 00:17:32	f	0		product	f
587	FORGET ME MOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 160	code128	VKWC 160	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:33	2024-08-03 00:17:33	f	0		product	f
588	GODFATHER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 166	code128	VKWC 166	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:33	2024-08-03 00:17:33	f	0		product	f
589	GREEN MAMBA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 137	code128	VKWC 137	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:33	2024-08-03 00:17:33	f	0		product	f
590	HAWAIIAN ADDICTION	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 148	code128	VKWC 148	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:33	2024-08-03 00:17:33	f	0		product	f
591	HOT CHICK MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 150	code128	VKWC 150	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:33	2024-08-03 00:17:33	f	0		product	f
592	HOT TIPSY	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 144	code128	VKWC 144	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:34	2024-08-03 00:17:34	f	0		product	f
593	ISLAND ESCAPADE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 143	code128	VKWC 143	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:34	2024-08-03 00:17:34	f	0		product	f
594	JAGERBOMB	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 165	code128	VKWC 165	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:34	2024-08-03 00:17:34	f	0		product	f
595	LICK HER RIGHT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 146	code128	VKWC 146	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:34	2024-08-03 00:17:34	f	0		product	f
596	LONG ISLAND ICED TEA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 177	code128	VKWC 177	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:35	2024-08-03 00:17:35	f	0		product	f
597	LOVE MAKING SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 153	code128	VKWC 153	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:35	2024-08-03 00:17:35	f	0		product	f
598	LYCHEE  MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 186	code128	VKWC 186	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:35	2024-08-03 00:17:35	f	0		product	f
599	LYCHEE DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 197	code128	VKWC 197	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:36	2024-08-03 00:17:36	f	0		product	f
600	LYCHEE MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 174	code128	VKWC 174	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:36	2024-08-03 00:17:36	f	0		product	f
601	MALEFICENT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 141	code128	VKWC 141	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:36	2024-08-03 00:17:36	f	0		product	f
602	MANGO  MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 172	code128	VKWC 172	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:37	2024-08-03 00:17:37	f	0		product	f
603	MANGO  MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 185	code128	VKWC 185	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:37	2024-08-03 00:17:37	f	0		product	f
604	MANGODAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 195	code128	VKWC 195	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:37	2024-08-03 00:17:37	f	0		product	f
605	NAKED LADY	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 147	code128	VKWC 147	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:38	2024-08-03 00:17:38	f	0		product	f
606	PINACOLADA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 167	code128	VKWC 167	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:38	2024-08-03 00:17:38	f	0		product	f
607	PINEAPPLE  MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 188	code128	VKWC 188	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:38	2024-08-03 00:17:38	f	0		product	f
608	PINEAPPLE DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 199	code128	VKWC 199	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:39	2024-08-03 00:17:39	f	0		product	f
609	PINEAPPLE MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 176	code128	VKWC 176	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:39	2024-08-03 00:17:39	f	0		product	f
610	QUEEN TASTE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 142	code128	VKWC 142	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:39	2024-08-03 00:17:39	f	0		product	f
611	SANGRIA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 193	code128	VKWC 193	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:39	2024-08-03 00:17:39	f	0		product	f
612	SATISFACTION SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 154	code128	VKWC 154	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:39	2024-08-03 00:17:39	f	0		product	f
617	THE SINNER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 136	code128	VKWC 136	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:41	2024-08-03 00:17:41	f	0		product	f
618	TROPICAL KISS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 168	code128	VKWC 168	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:41	2024-08-03 00:17:41	f	0		product	f
619	VENTANA GOLD	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 145	code128	VKWC 145	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:41	2024-08-03 00:17:41	f	0		product	f
620	VIRGIN SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 155	code128	VKWC 155	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:41	2024-08-03 00:17:41	f	0		product	f
621	VODKA LOVER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 152	code128	VKWC 152	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:42	2024-08-03 00:17:42	f	0		product	f
622	WATERMELON   MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 187	code128	VKWC 187	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:42	2024-08-03 00:17:42	f	0		product	f
623	WATERMELON DAIQUIRI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 198	code128	VKWC 198	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:42	2024-08-03 00:17:42	f	0		product	f
624	WATERMELON MARGARITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 175	code128	VKWC 175	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:42	2024-08-03 00:17:42	f	0		product	f
625	WE CHILL ON THE BEACH	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 140	code128	VKWC 140	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:43	2024-08-03 00:17:43	f	0		product	f
626	WE CHILL PARTY	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 149	code128	VKWC 149	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:43	2024-08-03 00:17:43	f	0		product	f
627	WENG-WENG	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 164	code128	VKWC 164	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:43	2024-08-03 00:17:43	f	0		product	f
628	WHITE RUSSIAN	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 181	code128	VKWC 181	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:43	2024-08-03 00:17:43	f	0		product	f
634	ANDY PLAYER 500ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 06	code128	VKWC 06	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:45	2024-08-03 00:17:45	f	0		product	f
635	EMPERADOR GOLD 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 12	code128	VKWC 12	\N	\N	30	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:45	2024-08-03 00:17:45	f	0		product	f
636	EMPERADOR GOLD 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 05	code128	VKWC 05	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:45	2024-08-03 00:17:45	f	0		product	f
637	EMPERADOR LIGHT  SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 09	code128	VKWC 09	\N	\N	30	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:46	2024-08-03 00:17:46	f	0		product	f
638	EMPERADOR LIGHT 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 02	code128	VKWC 02	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:46	2024-08-03 00:17:46	f	0		product	f
639	EMPERADOR LIGHT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 01	code128	VKWC 01	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:46	2024-08-03 00:17:46	f	0		product	f
640	FUNDADOR GOLD 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 04	code128	VKWC 04	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:46	2024-08-03 00:17:46	f	0		product	f
641	FUNDADOR GOLD SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 11	code128	VKWC 11	\N	\N	30	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:46	2024-08-03 00:17:46	f	0		product	f
642	FUNDADOR LIGHT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 03	code128	VKWC 03	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:47	2024-08-03 00:17:47	f	0		product	f
643	FUNDADOR LIGHT SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 10	code128	VKWC 10	\N	\N	30	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:47	2024-08-03 00:17:47	f	0		product	f
645	JURA ORIGIN 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 07	code128	VKWC 07	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:47	2024-08-03 00:17:47	f	0		product	f
646	THE DALMORE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 08	code128	VKWC 08	\N	\N	30	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:48	2024-08-03 00:17:48	f	0		product	f
648	BOMBAY GIN 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 36	code128	VKWC 36	\N	\N	31	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:48	2024-08-03 00:17:48	f	0		product	f
686	MINTY CUCUMBER LYCHEE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 131	code128	VKWC 131	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:57	2024-08-03 00:17:57	f	0		product	f
687	SLUSHIE LEMONADE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 123	code128	VKWC 123	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:57	2024-08-03 00:17:57	f	0		product	f
688	THE SPLASHER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 127	code128	VKWC 127	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:58	2024-08-03 00:17:58	f	0		product	f
689	TROPICAL BLAST	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 134	code128	VKWC 134	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:58	2024-08-03 00:17:58	f	0		product	f
690	VIRGIN CUCUMBER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 124	code128	VKWC 124	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:58	2024-08-03 00:17:58	f	0		product	f
691	VS MOCKTAIL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 120	code128	VKWC 120	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:58	2024-08-03 00:17:58	f	0		product	f
692	WC TROPICAL STIR	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 121	code128	VKWC 121	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:59	2024-08-03 00:17:59	f	0		product	f
693	BAILEYS 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 77	code128	VKWC 77	\N	\N	36	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:59	2024-08-03 00:17:59	f	0		product	f
694	BAILEYS SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 83	code128	VKWC 83	\N	\N	36	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:59	2024-08-03 00:17:59	f	0		product	f
695	CAMPARI 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 78	code128	VKWC 78	\N	\N	36	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:59	2024-08-03 00:17:59	f	0		product	f
696	CAMPARI SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 84	code128	VKWC 84	\N	\N	36	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:59	2024-08-03 00:17:59	f	0		product	f
697	JAGERMAEISTER 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 79	code128	VKWC 79	\N	\N	36	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:00	2024-08-03 00:18:00	f	0		product	f
698	JAGERMAEISTER SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 85	code128	VKWC 85	\N	\N	36	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:00	2024-08-03 00:18:00	f	0		product	f
699	KAHLUA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 80	code128	VKWC 80	\N	\N	36	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:00	2024-08-03 00:18:00	f	0		product	f
700	KAHLUA SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 86	code128	VKWC 86	\N	\N	36	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:00	2024-08-03 00:18:00	f	0		product	f
701	MARTINI EXTRA DRY SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 87	code128	VKWC 87	\N	\N	36	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:01	2024-08-03 00:18:01	f	0		product	f
702	MARTINI EXTRA DRY1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 81	code128	VKWC 81	\N	\N	36	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:01	2024-08-03 00:18:01	f	0		product	f
644	JURA ORIGIN SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 14	code128	VKWC 14	\N	\N	30	0	6	prevent-sales	f	t	12	\N	2024-08-03 00:17:47	2024-10-21 17:38:31	f	0		product	f
633	ANDY PLAYER SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 13	code128	VKWC 13	\N	\N	30	0	6	prevent-sales	f	t	12	\N	2024-08-03 00:17:45	2024-10-21 17:38:06	f	0		product	f
703	TEQUILA ROSE 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 82	code128	VKWC 82	\N	\N	36	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:01	2024-08-03 00:18:01	f	0		product	f
704	CABERNET SAUVIGNON	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 89	code128	VKWC 89	\N	\N	37	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:01	2024-08-03 00:18:01	f	0		product	f
706	MERLOT 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 90	code128	VKWC 90	\N	\N	37	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:02	2024-08-03 00:18:02	f	0		product	f
708	SHIRAZ	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 91	code128	VKWC 91	\N	\N	37	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:02	2024-08-03 00:18:02	f	0		product	f
710	BACARDI BLACK 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 67	code128	VKWC 67	\N	\N	38	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:03	2024-08-03 00:18:03	f	0		product	f
711	BACARDI BLACK SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 72	code128	VKWC 72	\N	\N	38	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:03	2024-08-03 00:18:03	f	0		product	f
712	BACARDI GOLD 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 68	code128	VKWC 68	\N	\N	38	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:03	2024-08-03 00:18:03	f	0		product	f
713	BACARDI GOLD SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 73	code128	VKWC 73	\N	\N	38	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:03	2024-08-03 00:18:03	f	0		product	f
714	BACARDI SUPERIOR 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 69	code128	VKWC 69	\N	\N	38	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:04	2024-08-03 00:18:04	f	0		product	f
715	BACARDI SUPERIOR SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 74	code128	VKWC 74	\N	\N	38	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:04	2024-08-03 00:18:04	f	0		product	f
716	CAPTAIN MORGAN 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 70	code128	VKWC 70	\N	\N	38	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:04	2024-08-03 00:18:04	f	0		product	f
717	CAPTAIN MORGAN SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 75	code128	VKWC 75	\N	\N	38	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:04	2024-08-03 00:18:04	f	0		product	f
718	LOCAL RUM	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 71	code128	VKWC 71	\N	\N	38	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:04	2024-08-03 00:18:04	f	0		product	f
719	LOCAL RUM SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 76	code128	VKWC 76	\N	\N	38	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:05	2024-08-03 00:18:05	f	0		product	f
554	CORONA EXTRA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 109	code128	VKWC 109	\N	\N	26	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:25	2024-08-03 00:17:25	f	0		product	f
555	HEINEKEN 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 107	code128	VKWC 107	\N	\N	26	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:25	2024-08-03 00:17:25	f	0		product	f
556	SAN MIGUEL APPLE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 106	code128	VKWC 106	\N	\N	26	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:25	2024-08-03 00:17:25	f	0		product	f
557	SAN MIGUEL LIGHT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 105	code128	VKWC 105	\N	\N	26	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:26	2024-08-03 00:17:26	f	0		product	f
558	SAN MIGUEL PALE PILSEN	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 104	code128	VKWC 104	\N	\N	26	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:26	2024-08-03 00:17:26	f	0		product	f
559	SMIRNOFF MULE 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 108	code128	VKWC 108	\N	\N	26	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:26	2024-08-03 00:17:26	f	0		product	f
560	LONG ISLAND ICED TEA TOWER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 204	code128	VKWC 204	\N	\N	27	0	4	prevent-sales	f	t	6	\N	2024-08-03 00:17:26	2024-08-03 00:17:26	f	0		product	f
561	MAI THAI TOWER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 200	code128	VKWC 200	\N	\N	27	0	4	prevent-sales	f	t	6	\N	2024-08-03 00:17:27	2024-08-03 00:17:27	f	0		product	f
562	RUM COKE TOWER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 201	code128	VKWC 201	\N	\N	27	0	4	prevent-sales	f	t	6	\N	2024-08-03 00:17:27	2024-08-03 00:17:27	f	0		product	f
563	SEX ON THE BEACH TOWER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 203	code128	VKWC 203	\N	\N	27	0	4	prevent-sales	f	t	6	\N	2024-08-03 00:17:27	2024-08-03 00:17:27	f	0		product	f
564	TEQUILA SUNRISE TOWER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 202	code128	VKWC 202	\N	\N	27	0	4	prevent-sales	f	t	6	\N	2024-08-03 00:17:27	2024-08-03 00:17:27	f	0		product	f
565	ADIOS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 183	code128	VKWC 183	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:27	2024-08-03 00:17:27	f	0		product	f
566	AMARETTO SOUR	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 180	code128	VKWC 180	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:28	2024-08-03 00:17:28	f	0		product	f
613	SEX ON THE BEACH	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 191	code128	VKWC 191	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:40	2024-08-03 00:17:40	f	0		product	f
614	SMIRKING PRIEST VODKA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 161	code128	VKWC 161	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:40	2024-08-03 00:17:40	f	0		product	f
615	TEQUILA SUNRISE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 192	code128	VKWC 192	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:40	2024-08-03 00:17:40	f	0		product	f
616	THE GRINCH	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 158	code128	VKWC 158	\N	\N	28	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:40	2024-08-03 00:17:40	f	0		product	f
649	BOMBAY GIN SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 39	code128	VKWC 39	\N	\N	31	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:48	2024-08-03 00:17:48	f	0		product	f
650	LOCAL GIN 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 38	code128	VKWC 38	\N	\N	31	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:48	2024-08-03 00:17:48	f	0		product	f
707	MERLOT GLASS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 93	code128	VKWC 93	\N	\N	37	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:18:02	2024-10-21 17:39:54	f	0		product	f
709	SHIRAZ GLASS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 94	code128	VKWC 94	\N	\N	37	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:18:02	2024-10-21 17:39:29	f	0		product	f
651	LOCAL GIN SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 41	code128	VKWC 41	\N	\N	31	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:49	2024-08-03 00:17:49	f	0		product	f
652	TANQUERAY 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 37	code128	VKWC 37	\N	\N	31	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:17:49	2024-08-03 00:17:49	f	0		product	f
653	TANQUERAY SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 40	code128	VKWC 40	\N	\N	31	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:17:49	2024-08-03 00:17:49	f	0		product	f
676	BANANA DELIGHT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 130	code128	VKWC 130	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:55	2024-08-03 00:17:55	f	0		product	f
677	BASIL LEMONADE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 135	code128	VKWC 135	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:55	2024-08-03 00:17:55	f	0		product	f
678	CARROT PANTHER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 128	code128	VKWC 128	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:55	2024-08-03 00:17:55	f	0		product	f
679	CRANBERRY SMASH	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 119	code128	VKWC 119	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:56	2024-08-03 00:17:56	f	0		product	f
680	DRUNK LOVE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 125	code128	VKWC 125	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:56	2024-08-03 00:17:56	f	0		product	f
681	FROZEN BUKO DELIGHT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 132	code128	VKWC 132	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:56	2024-08-03 00:17:56	f	0		product	f
682	GINGER HONEY	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 126	code128	VKWC 126	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:56	2024-08-03 00:17:56	f	0		product	f
683	KUDETAH LAYERED MOCKTAIL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 122	code128	VKWC 122	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:56	2024-08-03 00:17:56	f	0		product	f
684	LEMON GRASS FIZZ	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 129	code128	VKWC 129	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:57	2024-08-03 00:17:57	f	0		product	f
685	MARGARITA MOCKTAIL	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 133	code128	VKWC 133	\N	\N	35	0	3	prevent-sales	f	t	6	\N	2024-08-03 00:17:57	2024-08-03 00:17:57	f	0		product	f
732	ELYSSE BRUT 	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 101	code128	VKWC 101	\N	\N	40	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:08	2024-08-03 00:18:08	f	0		product	f
733	ELYSSE DEMI-SEC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 102	code128	VKWC 102	\N	\N	40	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:08	2024-08-03 00:18:08	f	0		product	f
734	MARTINI PROSECCO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 103	code128	VKWC 103	\N	\N	40	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:08	2024-08-03 00:18:08	f	0		product	f
735	JOSE CUERVO GOLD 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 56	code128	VKWC 56	\N	\N	41	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:09	2024-08-03 00:18:09	f	0		product	f
736	JOSE CUERVO GOLD 750ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 55	code128	VKWC 55	\N	\N	41	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:09	2024-08-03 00:18:09	f	0		product	f
737	JOSE CUERVO GOLD SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 61	code128	VKWC 61	\N	\N	41	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:09	2024-08-03 00:18:09	f	0		product	f
738	JOSE CUERVO SILVER 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 57	code128	VKWC 57	\N	\N	41	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:09	2024-08-03 00:18:09	f	0		product	f
739	JOSE CUERVO SILVER SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 63	code128	VKWC 63	\N	\N	41	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:10	2024-08-03 00:18:10	f	0		product	f
740	LOCAL TEQUILA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 60	code128	VKWC 60	\N	\N	41	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:10	2024-08-03 00:18:10	f	0		product	f
741	LOCAL TEQUILA SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 66	code128	VKWC 66	\N	\N	41	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:10	2024-08-03 00:18:10	f	0		product	f
742	PATRON ANEJO 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 58	code128	VKWC 58	\N	\N	41	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:10	2024-08-03 00:18:10	f	0		product	f
743	PATRON ANEJO SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 64	code128	VKWC 64	\N	\N	41	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:10	2024-08-03 00:18:10	f	0		product	f
744	PATRON ANEJO SILVER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 59	code128	VKWC 59	\N	\N	41	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:11	2024-08-03 00:18:11	f	0		product	f
745	PATRON ANEJO SILVER SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 65	code128	VKWC 65	\N	\N	41	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:11	2024-08-03 00:18:11	f	0		product	f
753	ABSOLUT CITRON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 44	code128	VKWC 44	\N	\N	43	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:13	2024-08-03 00:18:13	f	0		product	f
754	ABSOLUT CITRON SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 50	code128	VKWC 50	\N	\N	43	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:13	2024-08-03 00:18:13	f	0		product	f
755	ABSOLUT KURANT 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 45	code128	VKWC 45	\N	\N	43	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:13	2024-08-03 00:18:13	f	0		product	f
757	ABSOLUT VODKA 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 43	code128	VKWC 43	\N	\N	43	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
758	ABSOLUT VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 42	code128	VKWC 42	\N	\N	43	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
759	ABSOLUT VODKA SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 49	code128	VKWC 49	\N	\N	43	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
760	BELVEDERE VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 47	code128	VKWC 47	\N	\N	43	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
761	BELVEDERE VODKA SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 53	code128	VKWC 53	\N	\N	43	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
762	GREYGOOSE VODKA 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 46	code128	VKWC 46	\N	\N	43	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
763	GREYGOOSE VODKA SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 52	code128	VKWC 52	\N	\N	43	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:14	2024-08-03 00:18:14	f	0		product	f
756	ABSOLUT KURANT SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 51	code128	VKWC 51	\N	\N	43	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:13	2024-08-03 00:18:13	f	0		product	f
765	LOCAL VODKA SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 54	code128	VKWC 54	\N	\N	43	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:15	2024-08-03 00:18:15	f	0		product	f
775	CHIVAS REGAL 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 16	code128	VKWC 16	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:17	2024-08-03 00:18:17	f	0		product	f
776	CHIVAS REGAL SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 27	code128	VKWC 27	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:17	2024-08-03 00:18:17	f	0		product	f
777	JACK DANIELS 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 26	code128	VKWC 26	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:18	2024-08-03 00:18:18	f	0		product	f
778	JACK DANIELS 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 25	code128	VKWC 25	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:18	2024-08-03 00:18:18	f	0		product	f
779	JACK DANIELS SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 35	code128	VKWC 35	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:18	2024-08-03 00:18:18	f	0		product	f
780	JAMESON 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 18	code128	VKWC 18	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:18	2024-08-03 00:18:18	f	0		product	f
781	JAMESON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 17	code128	VKWC 17	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:18	2024-08-03 00:18:18	f	0		product	f
782	JAMESON SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 28	code128	VKWC 28	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:19	2024-08-03 00:18:19	f	0		product	f
783	JIMBEAM BALCK 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 20	code128	VKWC 20	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:19	2024-08-03 00:18:19	f	0		product	f
784	JIMBEAM BLACK SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 30	code128	VKWC 30	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:19	2024-08-03 00:18:19	f	0		product	f
785	JIMBEAM BOURBON 700ML	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 19	code128	VKWC 19	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:19	2024-08-03 00:18:19	f	0		product	f
786	JIMBEAM BOURBON SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 29	code128	VKWC 29	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:20	2024-08-03 00:18:20	f	0		product	f
787	JOHNNIE WALKER BLACK LABEL 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 21	code128	VKWC 21	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:20	2024-08-03 00:18:20	f	0		product	f
788	JOHNNIE WALKER BLACK LABEL SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 31	code128	VKWC 31	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:20	2024-08-03 00:18:20	f	0		product	f
789	JOHNNIE WALKER BLUE LABEL 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 23	code128	VKWC 23	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:20	2024-08-03 00:18:20	f	0		product	f
790	JOHNNIE WALKER BLUE LABEL SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 33	code128	VKWC 33	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:20	2024-08-03 00:18:20	f	0		product	f
791	JOHNNIE WALKER DOUBLE BLACK 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 22	code128	VKWC 22	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:21	2024-08-03 00:18:21	f	0		product	f
792	JOHNNIE WALKER DOUBLE BLACK SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 32	code128	VKWC 32	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:21	2024-08-03 00:18:21	f	0		product	f
793	JOHNNIE WALKER RED LABEL 1L	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 24	code128	VKWC 24	\N	\N	45	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:21	2024-08-03 00:18:21	f	0		product	f
794	JOHNNIE WALKER RED LABEL SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 34	code128	VKWC 34	\N	\N	45	0	6	prevent-sales	f	t	6	\N	2024-08-03 00:18:21	2024-08-03 00:18:21	f	0		product	f
795	CHARDONNAY	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 96	code128	VKWC 96	\N	\N	46	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:22	2024-08-03 00:18:22	f	0		product	f
797	SAUVIGNON BLANC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 95	code128	VKWC 95	\N	\N	46	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:22	2024-08-03 00:18:22	f	0		product	f
799	WHITE MOSCATO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 97	code128	VKWC 97	\N	\N	46	0	5	prevent-sales	f	t	6	\N	2024-08-03 00:18:23	2024-08-03 00:18:23	f	0		product	f
827	MANGO SALSA (ADD-ON)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU039	code128	KU039	\N	\N	49	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:57	2024-08-03 01:13:57	f	0		product	t
828	PAN DE SAL (ADD-ON)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU038	code128	KU038	\N	\N	49	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:58	2024-08-03 01:13:58	f	0		product	t
829	SWEET POTATO CHIPS (ADD-ON)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU040	code128	KU040	\N	\N	49	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:58	2024-08-03 01:13:58	f	0		product	t
830	BRUSCHETTA FUNGHI CON TRIFOLATI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU001	code128	KU001	\N	\N	50	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:58	2024-08-03 01:13:58	f	0		product	t
831	BRUSCHETTA PROSCIUTTO DI PARMA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU003	code128	KU003	\N	\N	50	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:58	2024-08-03 01:13:58	f	0		product	t
832	BRUSCHETTA RAGU DI FUNGHI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU002	code128	KU002	\N	\N	50	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:58	2024-08-03 01:13:58	f	0		product	t
764	LOCAL VODKA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 48	code128	VKWC 48	\N	\N	43	0	5	prevent-sales	f	t	12	\N	2024-08-03 00:18:15	2024-10-21 18:27:09	f	0		product	f
796	CHARDONNAY GLASS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 99	code128	VKWC 99	\N	\N	46	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:18:22	2024-10-21 17:40:35	f	0		product	f
833	FAJITAS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU004	code128	KU004	\N	\N	50	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:59	2024-08-03 01:13:59	f	0		product	t
834	BANANA CON QUEZO TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU045	code128	KU045	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:59	2024-08-03 01:13:59	f	0		product	t
835	BANANA FRITTERS	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU043	code128	KU043	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:59	2024-08-03 01:13:59	f	0		product	t
836	BROWNIE A LA MODE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU048	code128	KU048	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:13:59	2024-08-03 01:13:59	f	0		product	t
838	COCO SEMIFREDDO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU047	code128	KU047	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:00	2024-08-03 01:14:00	f	0		product	t
840	MANGO QUEZO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU049	code128	KU049	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:00	2024-08-03 01:14:00	f	0		product	t
841	PLATANO DE CARAMELLO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU046	code128	KU046	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:01	2024-08-03 01:14:01	f	0		product	t
842	SEASONAL FRUIT PLATTER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU054	code128	KU054	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:01	2024-08-03 01:14:01	f	0		product	t
843	SPECIAL TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU044	code128	KU044	\N	\N	51	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:01	2024-08-03 01:14:01	f	0		product	t
846	BACON (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU023	code128	KU023	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:02	2024-08-03 01:14:02	f	0		product	t
847	CHEESE (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU031	code128	KU031	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:02	2024-08-03 01:14:02	f	0		product	t
848	CHICKEN  (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU028	code128	KU028	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:02	2024-08-03 01:14:02	f	0		product	t
849	HAM  (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU026	code128	KU026	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:02	2024-08-03 01:14:02	f	0		product	t
850	MEAT  (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU025	code128	KU025	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:03	2024-08-03 01:14:03	f	0		product	t
851	MUSHROOM (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU029	code128	KU029	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:03	2024-08-03 01:14:03	f	0		product	t
852	OLIVES (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU030	code128	KU030	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:03	2024-08-03 01:14:03	f	0		product	t
853	PEPPERONI  (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU024	code128	KU024	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:03	2024-08-03 01:14:03	f	0		product	t
854	SHRIMP  (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU027	code128	KU027	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:04	2024-08-03 01:14:04	f	0		product	t
855	VEGGIES (EXTRA)	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU032	code128	KU032	\N	\N	52	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:04	2024-08-03 01:14:04	f	0		product	t
856	AGLIO ANGEL HAIR	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU011	code128	KU011	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:04	2024-08-03 01:14:04	f	0		product	t
857	BOLOGNESE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU009	code128	KU009	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:04	2024-08-03 01:14:04	f	0		product	t
858	CARBONARA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU005	code128	KU005	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:04	2024-08-03 01:14:04	f	0		product	t
859	CHICKEN ALFREDO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU010	code128	KU010	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:05	2024-08-03 01:14:05	f	0		product	t
860	LA VERDURA PESTO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU006	code128	KU006	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:05	2024-08-03 01:14:05	f	0		product	t
861	LINGUINE CON LE VONGOLE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU012	code128	KU012	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:05	2024-08-03 01:14:05	f	0		product	t
862	SALMON SPAGHETTI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU007	code128	KU007	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:05	2024-08-03 01:14:05	f	0		product	t
863	SPANISH SARDINES PASTA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU008	code128	KU008	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:06	2024-08-03 01:14:06	f	0		product	t
864	TUFFLE OIL MUSHROOM PASTA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU013	code128	KU013	\N	\N	53	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:06	2024-08-03 01:14:06	f	0		product	t
865	CRUNCHY CHICKEN CEREAL	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU034	code128	KU034	\N	\N	54	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:06	2024-08-03 01:14:06	f	0		product	t
866	ITALIAN TENDER BEEF BULGOGI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU035	code128	KU035	\N	\N	54	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:06	2024-08-03 01:14:06	f	0		product	t
867	JUICYCRISPY CHICKEN MILANESE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU033	code128	KU033	\N	\N	54	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:07	2024-08-03 01:14:07	f	0		product	t
868	KUDETAH PORCHETTA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU036	code128	KU036	\N	\N	54	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:07	2024-08-03 01:14:07	f	0		product	t
869	SHRIMP GASBAS CON PEPPERONI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU037	code128	KU037	\N	\N	54	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:07	2024-08-03 01:14:07	f	0		product	t
844	UBE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU051	code128	KU051	\N	\N	51	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:14:01	2024-10-17 12:43:00	f	0		product	t
839	MANGO ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU052	code128	KU052	\N	\N	51	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:14:00	2024-10-17 12:43:25	f	0		product	t
837	CHOCOLATE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU053	code128	KU053	\N	\N	51	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:14:00	2024-10-17 12:44:07	f	0		product	t
870	CHAIRMANS BURGER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU041	code128	KU041	\N	\N	55	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:07	2024-08-03 01:14:07	f	0		product	t
871	CLUB SANDWICH	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU042	code128	KU042	\N	\N	55	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:08	2024-08-03 01:14:08	f	0		product	t
872	BLUEWATER	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU017	code128	KU017	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:08	2024-08-03 01:14:08	f	0		product	t
873	CHIAIRMANS CHIOCE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU019	code128	KU019	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:09	2024-08-03 01:14:09	f	0		product	t
874	GRASS SKIRT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU022	code128	KU022	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:09	2024-08-03 01:14:09	f	0		product	t
875	HAWAIIAN	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU014	code128	KU014	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:09	2024-08-03 01:14:09	f	0		product	t
876	ITALIAN BELLA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU018	code128	KU018	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:09	2024-08-03 01:14:09	f	0		product	t
877	KUNG PAO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU016	code128	KU016	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:09	2024-08-03 01:14:09	f	0		product	t
878	LA MARGHERITA	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU015	code128	KU015	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:10	2024-08-03 01:14:10	f	0		product	t
879	OVERLOAD CHEESE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU020	code128	KU020	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:10	2024-08-03 01:14:10	f	0		product	t
880	PEPPERONI	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU021	code128	KU021	\N	\N	56	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:14:10	2024-08-03 01:14:10	f	0		product	t
802	BANANA CON QUEZO TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN016	code128	VEN016	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:14	2024-08-03 00:45:14	f	0		product	t
803	BANANA FRITTERS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN014	code128	VEN014	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:14	2024-08-03 00:45:14	f	0		product	t
804	BROWNIE A LA MODE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN019	code128	VEN019	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:14	2024-08-03 00:45:14	f	0		product	t
806	COCO SEMIFREDDO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN018	code128	VEN018	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:15	2024-08-03 00:45:15	f	0		product	t
808	MANGO QUEZO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN020	code128	VEN020	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:15	2024-08-03 00:45:15	f	0		product	t
809	PLATANO DE CARAMELLO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN017	code128	VEN017	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:15	2024-08-03 00:45:15	f	0		product	t
810	SEASONAL FRUIT PLATTER	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN025	code128	VEN025	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:15	2024-08-03 00:45:15	f	0		product	t
811	SPECIAL TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN015	code128	VEN015	\N	\N	47	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:15	2024-08-03 00:45:15	f	0		product	t
814	BEEF SALPICAO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN006	code128	VEN006	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:16	2024-08-03 00:45:16	f	0		product	t
815	BUMBU SATAY CHICKEN	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN010	code128	VEN010	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:16	2024-08-03 00:45:16	f	0		product	t
816	CALAMARES	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN003	code128	VEN003	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:17	2024-08-03 00:45:17	f	0		product	t
817	CHICKEN KARAAGE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN001	code128	VEN001	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:17	2024-08-03 00:45:17	f	0		product	t
818	CHICKEN POPCORN	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN013	code128	VEN013	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:17	2024-08-03 00:45:17	f	0		product	t
819	CRISPY TIGER PRAWNS TEMPURA	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN004	code128	VEN004	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:17	2024-08-03 00:45:17	f	0		product	t
820	CRUSTED GOLDEN TIGER PRAWNS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN009	code128	VEN009	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:17	2024-08-03 00:45:17	f	0		product	t
821	GOCHUJANG WINGS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN002	code128	VEN002	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:18	2024-08-03 00:45:18	f	0		product	t
822	SALMON SASHIMI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN012	code128	VEN012	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:18	2024-08-03 00:45:18	f	0		product	t
823	SHRIMP ALA POBRE	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN008	code128	VEN008	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:18	2024-08-03 00:45:18	f	0		product	t
824	SIZZLING TOFU PUFFS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN011	code128	VEN011	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:18	2024-08-03 00:45:18	f	0		product	t
825	TIGER PRAWNS GAMBAS CON CHORIZO	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN005	code128	VEN005	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:19	2024-08-03 00:45:19	f	0		product	t
826	TUNA TATAKI	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN007	code128	VEN007	\N	\N	48	0	2	prevent-sales	f	t	6	\N	2024-08-03 00:45:19	2024-08-03 00:45:19	f	0		product	t
881	BEEF SALPICAO	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC010	code128	WC010	\N	\N	57	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:08	2024-08-03 01:30:08	f	0		product	t
813	VANILLA ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN021	code128	VEN021	\N	\N	47	0	2	prevent-sales	f	t	11	\N	2024-08-03 00:45:16	2024-10-17 12:45:14	f	0		product	t
807	MANGO ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN023	code128	VEN023	\N	\N	47	0	2	prevent-sales	f	t	11	\N	2024-08-03 00:45:15	2024-10-17 12:45:53	f	0		product	t
882	CRISPY TIGER PRAWNS TEMPURA	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC009	code128	WC009	\N	\N	57	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:08	2024-08-03 01:30:08	f	0		product	t
883	LAING PRAWNS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC011	code128	WC011	\N	\N	57	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:08	2024-08-03 01:30:08	f	0		product	t
884	PAKSIW NA SALMON	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC008	code128	WC008	\N	\N	57	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:08	2024-08-03 01:30:08	f	0		product	t
885	TOFU BAY LEAF PORK	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC007	code128	WC007	\N	\N	57	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:08	2024-08-03 01:30:08	f	0		product	t
886	BANANA CON QUEZO TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC040	code128	WC040	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:09	2024-08-03 01:30:09	f	0		product	t
887	BANANA FRITTERS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC038	code128	WC038	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:09	2024-08-03 01:30:09	f	0		product	t
888	BROWNIE A LA MODE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC043	code128	WC043	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:09	2024-08-03 01:30:09	f	0		product	t
890	COCO SEMIFREDDO	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC042	code128	WC042	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:10	2024-08-03 01:30:10	f	0		product	t
892	MANGO QUEZO	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC044	code128	WC044	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:10	2024-08-03 01:30:10	f	0		product	t
893	PLATANO DE CARAMELLO	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC041	code128	WC041	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:10	2024-08-03 01:30:10	f	0		product	t
894	SEASONAL FRUIT PLATTER	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC049	code128	WC049	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:11	2024-08-03 01:30:11	f	0		product	t
895	SPECIAL TURON	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC039	code128	WC039	\N	\N	58	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:11	2024-08-03 01:30:11	f	0		product	t
898	GREEN MANGO SALAD	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC003	code128	WC003	\N	\N	59	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:11	2024-08-03 01:30:11	f	0		product	t
899	ORANGE WALNUT	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC001	code128	WC001	\N	\N	59	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:12	2024-08-03 01:30:12	f	0		product	t
900	WC ROKA SALATA	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC002	code128	WC002	\N	\N	59	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:12	2024-08-03 01:30:12	f	0		product	t
901	BEEF KEBAB	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC017	code128	WC017	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:12	2024-08-03 01:30:12	f	0		product	t
902	BONELESS SIZZLING BANGUS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC025	code128	WC025	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:12	2024-08-03 01:30:12	f	0		product	t
907	GLAZED GRILLED PORK CHOP	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC012	code128	WC012	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:14	2024-08-03 01:30:14	f	0		product	t
903	BRAZILIAN MARINATED FLANK STEAK	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC015	code128	WC015	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:13	2024-08-03 01:30:13	f	0		product	t
904	BRAZILIAN PICANHA CON ALHO WITH CARAMELIZED BANANA	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC016	code128	WC016	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:13	2024-08-03 01:30:13	f	0		product	t
905	CHICKEN ALA POBRE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC023	code128	WC023	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:13	2024-08-03 01:30:13	f	0		product	t
906	CHICKEN KIEV	inclusive	1	0	product	dematerialized	f	f	available	disabled	KFODeULqwN	code128	mains--chicken-kiev--DJydX	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:13	2024-08-03 01:30:13	f	0		product	t
908	GREEK CHICKEN MILANESE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC022	code128	WC022	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:14	2024-08-03 01:30:14	f	0		product	t
909	GRILLED GARLIC TIGER PRAWNS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC021	code128	WC021	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:14	2024-08-03 01:30:14	f	0		product	t
910	GRILLED PORK BELLY	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC013	code128	WC013	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:14	2024-08-03 01:30:14	f	0		product	t
911	MOROCCAN LAMB SHANK	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC018	code128	WC018	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:15	2024-08-03 01:30:15	f	0		product	t
912	PERSIAN BBQ CHICKEN	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC020	code128	WC020	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:15	2024-08-03 01:30:15	f	0		product	t
913	RENDANG LAMB RACK	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC019	code128	WC019	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:15	2024-08-03 01:30:15	f	0		product	t
914	SMOKER CHARCOALED STICKY BABY BACK RIBS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC014	code128	WC014	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:15	2024-08-03 01:30:15	f	0		product	t
915	SWEET BASIL SALMON	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC024	code128	WC024	\N	\N	60	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:15	2024-08-03 01:30:15	f	0		product	t
916	CHAO FUN BVORACAY FRIED RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC028	code128	WC028	\N	\N	61	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:15	2024-08-03 01:30:15	f	0		product	t
917	PLAIN RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC031	code128	WC031	\N	\N	61	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:16	2024-08-03 01:30:16	f	0		product	t
896	UBE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC046	code128	WC046	\N	\N	58	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:30:11	2024-10-17 12:40:47	f	0		product	t
891	MANGO ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC047	code128	WC047	\N	\N	58	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:30:10	2024-10-17 12:41:21	f	0		product	t
889	CHOCOLATE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC048	code128	WC048	\N	\N	58	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:30:09	2024-10-17 12:41:44	f	0		product	t
919	SINANGAG NA KANIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC030	code128	WC030	\N	\N	61	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:16	2024-08-03 01:30:16	f	0		product	t
920	FEAST OF FLAVORS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC051	code128	WC051	\N	\N	62	0	10	prevent-sales	f	t	6	\N	2024-08-03 01:30:16	2024-08-03 01:30:16	f	0		product	t
921	RELAXING ROMANCE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC050	code128	WC050	\N	\N	62	0	10	prevent-sales	f	t	6	\N	2024-08-03 01:30:17	2024-08-03 01:30:17	f	0		product	t
922	FRENCH FRIES	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC036	code128	WC036	\N	\N	63	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:17	2024-08-03 01:30:17	f	0		product	t
923	SWEET POTATO CHIPS	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC037	code128	WC037	\N	\N	63	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:17	2024-08-03 01:30:17	f	0		product	t
924	BRAZILIAN MOQUECA FISH STEW	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC004	code128	WC004	\N	\N	64	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:18	2024-08-03 01:30:18	f	0		product	t
925	BULALO	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC006	code128	WC006	\N	\N	64	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:18	2024-08-03 01:30:18	f	0		product	t
926	TOM KHA GAI	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC005	code128	WC005	\N	\N	64	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:18	2024-08-03 01:30:18	f	0		product	t
927	BEEF TENDERLOIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC034	code128	WC034	\N	\N	65	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:18	2024-08-03 01:30:18	f	0		product	t
928	CHICKEN FILLET	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC032	code128	WC032	\N	\N	65	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:19	2024-08-03 01:30:19	f	0		product	t
929	PORK TENDERLOIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC033	code128	WC033	\N	\N	65	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:19	2024-08-03 01:30:19	f	0		product	t
930	VEGETABLES  TENDERLOIN	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC035	code128	WC035	\N	\N	65	0	2	prevent-sales	f	t	6	\N	2024-08-03 01:30:19	2024-08-03 01:30:19	f	0		product	t
918	SHRIMP FRIED RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC029	code128	WC029	\N	\N	61	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:30:16	2024-10-02 13:54:20	f	0		product	t
243	SHRIMP FRIED RICE	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-F23	code128	KDA-F23	\N	\N	10	0	2	prevent-sales	f	t	11	\N	2024-07-16 19:57:23	2024-10-02 13:58:31	f	0		product	t
934	MELTING LOVE (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	EiqxT6MiiQ	code128	cocktails--melting-love--6uQE5	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:28:30	2024-10-17 16:38:32	f	0		product	f
897	VANILLA ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	WC045	code128	WC045	\N	\N	58	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:30:11	2024-10-17 12:40:15	f	0		product	t
845	VANILLA ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	KU050	code128	KU050	\N	\N	51	0	2	prevent-sales	f	t	11	\N	2024-08-03 01:14:02	2024-10-17 12:42:09	f	0		product	t
812	UBE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN022	code128	VEN022	\N	\N	47	0	2	prevent-sales	f	t	11	\N	2024-08-03 00:45:16	2024-10-17 12:44:38	f	0		product	t
805	CHOCOLATE ICE CREAM	inclusive	1	0	product	dematerialized	f	f	available	disabled	VEN024	code128	VEN024	\N	\N	47	0	2	prevent-sales	f	t	11	\N	2024-08-03 00:45:14	2024-10-17 12:47:00	f	0		product	t
931	GIN TONIC	inclusive	1	0	product	dematerialized	f	f	available	disabled	j3VsQVSYMB	code128	liquor--gin-tonic--8pEpu	\N	\N	12	0	2	prevent-sales	f	t	11	\N	2024-10-17 12:02:10	2024-10-17 12:52:35	f	0		product	f
661	ICED AMERICANO WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 238	code128	VKWC 238	\N	\N	33	0	3	prevent-sales	f	t	11	\N	2024-08-03 00:17:51	2024-10-17 13:35:32	f	0		product	t
933	GREEN MINDED (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	dMtdG7FhLE	code128	cocktails--green-minded--Xunxz	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:25:53	2024-10-17 16:39:32	f	0		product	f
935	SOUR MODE (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	fC2gb5D3Nn	code128	cocktails--sour-mode--7J6z2	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:29:32	2024-10-17 16:38:45	f	0		product	f
942	ZOMBIE (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	aSi9oreYGN	code128	cocktails--zombie-adp--uE3YR	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:36:34	2024-10-17 16:36:34	f	0		product	f
941	WENG-WENG (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	li523XUt5i	code128	cocktails--weng-weng--3eTik	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:35:19	2024-10-17 16:36:55	f	0		product	f
940	SANGRIA (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	W4VhjEIWdC	code128	cocktails--sangria--MmAr3	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:34:23	2024-10-17 16:37:09	f	0		product	f
939	ADIOS (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	s4tVIZXsuO	code128	cocktails--adios--xgohS	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:33:12	2024-10-17 16:37:21	f	0		product	f
938	GENIUS (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	ViMlbVH5Db	code128	cocktails--genius--fQaVk	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:32:23	2024-10-17 16:37:32	f	0		product	f
937	BLUE PAJAMAS (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	IyH9rZ6i8I	code128	cocktails--blue-pajamas--1ZIsN	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:31:35	2024-10-17 16:37:46	f	0		product	f
936	PLEASURE (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	0VA71r7Aaa	code128	cocktails--pleasure--j1Hct	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:30:30	2024-10-17 16:38:11	f	0		product	f
932	PUSHY RUM COKE (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	BqZudHzUR4	code128	cocktails--pushy-rum-coke--Z8C0j	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:24:16	2024-10-17 16:38:59	f	0		product	f
943	SCREW DRIVER (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	CfwP68DBRJ	code128	cocktails--screw-driver-adp--tEfr0	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:40:28	2024-10-17 16:40:28	f	0		product	f
944	BORACAY LONG ISLAND (ADP)	inclusive	1	0	product	dematerialized	f	f	available	disabled	yoralPnGtW	code128	cocktails--boracay-long-island-adp--oOoR4	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 16:41:43	2024-10-17 16:41:43	f	0		product	f
945	THE GRINCH	inclusive	1	0	product	dematerialized	f	f	available	disabled	SijDu3gc3I	code128	cocktails--the-grinch--5LOgt	\N	\N	6	0	2	prevent-sales	f	t	12	\N	2024-10-17 19:01:28	2024-10-17 19:01:28	f	0		product	f
946	THE SINNER	inclusive	1	0	product	dematerialized	f	f	available	disabled	itDrlb4n8V	code128	cocktails--the-sinner--bvJWW	\N	\N	28	0	2	prevent-sales	f	t	12	\N	2024-10-18 18:37:31	2024-10-18 18:37:31	f	0		product	f
165	CLASSIC MOJITO	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B59	code128	KDA-B59	\N	\N	6	0	3	prevent-sales	f	t	12	\N	2024-07-16 19:57:16	2024-10-20 16:51:29	f	0		product	f
947	MAI TAI TOWER	inclusive	1	0	product	dematerialized	f	f	available	disabled	v6bkKK7uzp	code128	cocktails--mai-tai-tower--qgbrJ	\N	\N	28	0	2	prevent-sales	f	t	12	\N	2024-10-20 18:31:18	2024-10-20 18:31:18	f	0		product	f
750	ICED SPANISH LATTE WC	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 243	code128	VKWC 243	\N	\N	7	0	8	prevent-sales	f	t	12	\N	2024-08-03 00:18:12	2024-10-21 13:07:59	f	0		product	t
647	THE DALMORE SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 15	code128	VKWC 15	\N	\N	30	0	6	prevent-sales	f	t	12	\N	2024-08-03 00:17:48	2024-10-21 17:39:11	f	0		product	f
800	WHITE MOSCATO GLASS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 100	code128	VKWC 100	\N	\N	46	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:18:23	2024-10-21 17:40:16	f	0		product	f
705	CABERNET SAUVIGNON GLASS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 92	code128	VKWC 92	\N	\N	37	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:18:01	2024-10-21 17:40:51	f	0		product	f
798	SAUVIGNON BLANC GLASS	inclusive	1	0	product	dematerialized	f	f	available	disabled	VKWC 98	code128	VKWC 98	\N	\N	46	0	3	prevent-sales	f	t	12	\N	2024-08-03 00:18:23	2024-10-21 17:41:12	f	0		product	f
279	CHIVAS REGAL 1L SHOT	inclusive	1	0	product	dematerialized	f	f	available	disabled	KDA-B122	code128	KDA-B122	\N	\N	12	0	6	prevent-sales	f	t	12	\N	2024-07-16 19:57:27	2024-10-21 17:43:04	f	0		product	f
\.


--
-- Name: nexopos_products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jojo
--

SELECT pg_catalog.setval('public.nexopos_products_id_seq', 948, true);


--
-- Name: nexopos_products nexopos_products_pkey; Type: CONSTRAINT; Schema: public; Owner: jojo
--

ALTER TABLE ONLY public.nexopos_products
    ADD CONSTRAINT nexopos_products_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

